<script>
    var resultApp = new Vue({
        el: '#result_app',
        data: {
            reservation_info: [],
            hotels: [],
            days: [],
            type_room_select_list: [],
            rooms_select: []
        },
        mounted() {

        },
        methods: {
            reset() {
                this.reservation_info = [];
                this.hotels = [];
                this.days = [];
                this.type_room_select_list = [];
                this.rooms_select = [];
                $('#result_app').hide();
            },
            show(data) {
                $('#result_app').show();
                let $this = this;
                this.hotels = data;
                this.days = [];
                this.hotels[0]['days'].forEach((item, index) => {
                    $this.days.push(item.date);
                });
                this.type_room_select_list = [];
                this.rooms_select = [];

            },
            type_room_select(hotel_id, type, action) {


                if (action == '+') {
                    if (type.quantity <= 2) {
                        type.quantity++;
                        this.rooms_action(type, action, hotel_id);
                    }
                } else {
                    if (type.quantity >= 1) {
                        type.quantity = parseInt(type.quantity) - 1;
                        this.rooms_action(type, action, hotel_id);
                    }
                }
            },
            room_select_change(room) {
                console.log(room);
                let formData = {
                    hotel_id: room.hotel_id,
                    checkin: room.checkin,
                    checkout: room.checkout,
                    type_id: room.type_id
                };
                $.ajax({
                    url: '{{ url('admin/reservas/buscar-disponibilidad') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    data: formData,
                }).done(function(data) {
                    
                        console.log(data.data[0].room_types_avails[0].total);
                        room.total = data.data[0].room_types_avails[0].total;

                    

                }).fail(function(error) {
                    console.error('Error al guardar el checkin:', error);
                }).always(function() {

                });
            },
            room_delete(index) {
                if (confirm('Seguro desea eliminar esta habitación?')) {
                    this.rooms_select.splice(index, 1);
                }
            },
            rooms_action(type, action, hotel_id) {
                console.log(type);
                if (action == '+') {
                    let date_range = this.daterange_get_values($('#search_daterange').val());

                    let children = 0;
                    if (this.rooms_select.length == 0) {
                        children = $('#search_children').val();
                    }
                    let total = 0;
                    Object.values(type.days).forEach((item, index) => {
                        total += parseFloat(item.price);
                    });

                    this.rooms_select.push({
                        type: type,
                        type_id: type.id,
                        checkin: date_range.from,
                        checkout: date_range.to,
                        adults: $('#search_adults').val(),
                        children: children,
                        notes: '',
                        total: total,
                        hotel_id: hotel_id
                    })
                } else {
                    for (let i = this.rooms_select.length - 1; i >= 0; i--) {
                        if (this.rooms_select[i].type_id === type.id) {
                            this.rooms_select.splice(i, 1);
                            break;
                        }
                    }
                }
            },
            info_client_modal() {
                clientModalApp.data_load(this.rooms_select);
                $('#client_modal').modal('show');
            },
            daterange_get_values(dates) {

                let dateSplit = dates.split('-');

                formattedFrom = dateSplit[0].trim();
                let [dia, mes, anio] = formattedFrom.split('/');
                formattedFrom = `${anio}-${mes}-${dia}`;

                formattedTo = dateSplit[1].trim();

                let [diaTo, mesTo, anioTo] = formattedTo.split('/');
                formattedTo = `${anioTo}-${mesTo}-${diaTo}`;

                return {
                    'from': formattedFrom,
                    'to': formattedTo
                }
            },
            date_format(fecha) {
                let [year, month, day] = fecha.split('-');
                return `${day}/${month}/${year}`;
            }
        },
        computed: {
            isButtonDisabled() {
                if (this.hotels.length > 0 && Array.isArray(this.hotels[0]['room_types_avails'])) {
                    return this.hotels[0]['room_types_avails'].reduce((sum, roomType) => sum + roomType
                        .quantity, 0) <= 0
                } else {
                    return true;
                }
            }
        },
    });
</script>
