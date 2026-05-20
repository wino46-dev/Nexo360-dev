<?php

namespace App\Http\Controllers\Api\V1\Admin;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTotemRequest;
use App\Http\Requests\UpdateTotemRequest;
use App\Http\Resources\Admin\TotemResource;
use App\Http\Resources\Admin\UserResource;
use App\Models\Totem;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class TotemApiController extends Controller
{
    private function resolveAuthenticatedUser(Request $request): ?User
    {
        // If a Bearer token is present, always prefer it over any session/cookie user.
        // This prevents a browser "web" session from overriding the intended API identity.
        $bearerToken = $request->bearerToken();
        if (!empty($bearerToken)) {
            $token = PersonalAccessToken::findToken($bearerToken);
            if ($token?->tokenable instanceof User) {
                return $token->tokenable;
            }
        }

        return $request->user();
    }

    private function resolveTotemIdForUser(User $user): ?int
    {
        if (!empty($user->totem_id)) {
            return (int) $user->totem_id;
        }

        $establecimientoIds = $user->establecimientos()->pluck('establecimientos.id');
        if ($establecimientoIds->isEmpty()) {
            return null;
        }

        return Totem::query()
            ->whereIn('establecimiento_id', $establecimientoIds)
            ->orderBy('id')
            ->value('id');
    }

    public function index()
    {
        abort_if(Gate::denies('totem_access'),
            Response::HTTP_FORBIDDEN,
            '403 Forbidden');

        return new TotemResource(Totem::with(['establecimiento'])->get());
    }

    public function store(StoreTotemRequest $request)
    {
        $totem = Totem::create($request->all());

        return (new TotemResource($totem))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Totem $totem)
    {
        abort_if(Gate::denies('totem_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TotemResource($totem->load(['establecimiento']));
    }

    public function profile(Request $request, Totem $totem)
    {

        $user = $this->resolveAuthenticatedUser($request);
        if (!$user) {
            return response(null, Response::HTTP_UNAUTHORIZED);
        }
        $totemId = $this->resolveTotemIdForUser($user);
        if (empty($totemId)) {
            return "Este usuario no tiene ningún totem asociado";
        }

        $userRecource = User::with([
            'totem',
            'totem.totemConfiguracionTpvs',
            'totem.totemConfiguracionGrabadors',
            'totem.establecimiento',
        ])->where('id', $user->id)->first();

        // Si el usuario no tenía `totem_id` persistido, forzamos la relación en la respuesta.
        if (empty($userRecource->totem_id)) {
            $userRecource->totem_id = $totemId;
            $userRecource->setRelation('totem', Totem::with([
                'totemConfiguracionTpvs',
                'totemConfiguracionGrabadors',
                'establecimiento',
            ])->find($totemId));
        }
        $userActual = User::with(['roles'])->where('id', $user->id)->get();
        $totemRoleTitle = (string) config('totem.role_title', 'Totem');

        if (isset($userActual[0]['roles'][0]['title'])) {
            if (strcasecmp($userActual[0]['roles'][0]['title'], $totemRoleTitle) === 0) {
                // NOTE: Do not use `env()` here; when `config:cache` is enabled Laravel won't load `.env`.
                // Read from config so values remain available in production.
                $userRecource->PUSHER_APP_ID = config('broadcasting.connections.pusher.app_id');
                $userRecource->PUSHER_APP_KEY = config('broadcasting.connections.pusher.key');
                $userRecource->PUSHER_APP_SECRET = config('broadcasting.connections.pusher.secret');
                $userRecource->PUSHER_HOST = config('broadcasting.connections.pusher.options.host');
                $userRecource->PUSHER_PORT = config('broadcasting.connections.pusher.options.port');
                $userRecource->PUSHER_SCHEME = config('broadcasting.connections.pusher.options.scheme');
                $userRecource->PUSHER_APP_CLUSTER = config('broadcasting.connections.pusher.options.cluster');

                $userRecource->PUSHER_CHANEL = config('totem.pusher.channel');
                $userRecource->PUSHER_EVENT_PAYMENT = config('totem.pusher.event_payment');
                $userRecource->PUSHER_EVENT_CARD = config('totem.pusher.event_card');
                $userRecource->PUSHER_EVENT_CARD_TESA = config('totem.pusher.event_card_tesa');

                $userRecource->PAYMENT_URI = config('totem.payment.uri');
                $userRecource->PAYMENT_RESPONSE_URI = config('totem.payment.response_uri');

                $userRecource->WRITE_CARD_URI = config('totem.endpoints.write_card_uri');
                $userRecource->CONTROL_ERROR_URI = config('totem.endpoints.control_error_uri');
            }
        }

        return new UserResource($userRecource);

    }

    public function phoneData(Request $request, Totem $totem){
        $user = $this->resolveAuthenticatedUser($request);
        if (!$user) {
            return response(null, Response::HTTP_UNAUTHORIZED);
        }
        $totemId = $this->resolveTotemIdForUser($user);
        if (!empty($totemId)) {
            $totemRecource = Totem::where('id', $totemId)->first();

            return new TotemResource($totemRecource);
        }

        return response(null, Response::HTTP_NO_CONTENT);


    }

    public function update(UpdateTotemRequest $request, Totem $totem)
    {
        $totem->update($request->all());

        return (new TotemResource($totem))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Totem $totem)
    {
        abort_if(Gate::denies('totem_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $totem->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
