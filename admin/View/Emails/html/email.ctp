
<table style="width: 100%;">
    <tr>
        <td>
            <table style="border: 1px solid rgb(244, 244, 244); margin: auto;width: 80%;">
                <tr>
                    <td>
                        <table style="margin-bottom: 20px;
                               max-width: 100%;
                               width: 100%;">
                            <tbody>
                                <tr style="background-color: #f9f9f9;">
                                    <td colspan="2">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Booking Detail</h3>
                                        </div><!-- /.box-header -->
                                    </td> 
                                </tr>
                                <tr>
                                    <th style=" padding: 8px;">Booking Reference</th> 
                                    <th><?php echo $data_array['Booking']['booking_reference']; ?></th>                                                 
                                </tr>
                                <tr>
                                    <td>Check in date</td>
                                    <td><?php echo $this->Custom->get_date($data_array['Booking']['check_in_date']); ?></td>                                                 
                                </tr>
                                <tr>
                                    <td>Check out date</td>
                                    <td><?php echo $this->Custom->get_date($data_array['Booking']['check_out_date']); ?></td>                                                 
                                </tr>
                                <tr>
                                    <td>Adult</td>
                                    <td><?php echo $data_array['Booking']['adult']; ?></td>                                                 
                                </tr>
                                <tr>
                                    <td>Child</td>
                                    <td><?php echo $data_array['Booking']['child']; ?></td>                                                 
                                </tr>
                                <tr>
                                    <td>No of room</td>
                                    <td><?php echo $data_array['Booking']['no_of_room']; ?></td>                                                 
                                </tr>
                                <tr>
                                    <td>Name</td>
                                    <td><?php echo $data_array['Booking']['title'] . ". " . $data_array['Booking']['first_name'] . " " . $data_array['Booking']['last_name']; ?></td>                                                 
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <td><?php echo $data_array['Booking']['address'] . ", " . $data_array['Booking']['city'] . ", " . $data_array['Booking']['post_code']; ?></td>                                                 
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td><?php echo $data_array['Booking']['email']; ?></td>                                                 
                                </tr>
                                <tr>
                                    <td>Mobile</td>
                                    <td><?php echo $data_array['Booking']['mobile']; ?></td>                                                 
                                </tr>
                                <tr>
                                    <td>Gender</td>
                                    <td><?php echo $data_array['Booking']['gender']; ?></td>                                                 
                                </tr>
                                <tr>
                                    <td>City</td>
                                    <td><?php echo $data_array['City']['name']; ?></td>                                                 
                                </tr>
                                <tr>
                                    <td>Country</td>
                                    <td><?php echo $data_array['Country']['name']; ?></td>                                                 
                                </tr> 
                                <tr>
                                    <th>Card charges</th>
                                    <th><?php echo Configure::read('Site.Currency') . number_format($data_array['Booking']['card_charges'], 2); ?></th>                                                 
                                </tr> 
                                <tr>
                                    <th>Discount</th>
                                    <th><?php echo Configure::read('Site.Currency') . number_format($data_array['Booking']['discount'], 2); ?></th>                                                 
                                </tr> 
                                <tr>
                                    <th>Booking amount</th>
                                    <th><?php echo Configure::read('Site.Currency') . number_format($data_array['Booking']['booking_amount'], 2); ?></th>                                                 
                                </tr> 
                                <tr>
                                    <td>Booking status</td>
                                    <td id="booking_status_<?php echo $data_array['Booking']['id']; ?>"><?php echo $this->Custom->booking_status($data_array['Booking']['booking_status'], $data_array['Booking']['id']); ?></td>
                                </tr>
                                <tr>
                                    <td>Ip</td>
                                    <td><?php echo $data_array['Booking']['ip']; ?></td>                                                 
                                </tr>
                                <tr>
                                    <td>Booking Date</td>
                                    <td><?php echo $data_array['Booking']['created']; ?></td>                                                 
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table class="table table-bordered">
                            <tbody>  
                                <tr>
                                    <td colspan="2">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Booking Item</h3>
                                        </div><!-- /.box-header -->
                                    </td>
                                </tr>
                                <tr>
                                    <td>Item type</td>
                                    <td><?php echo $this->Custom->item_type($data_array['BookingItem']['item_type']); ?></td>                                                 
                                </tr> 
                                <tr>
                                    <td>Name</td>
                                    <td><?php echo $data_array['BookingItem']['Hotel']['name']; ?></td>                                                 
                                </tr>
                                <tr>
                                    <td>Reception Telephone</td>
                                    <td><?php echo $data_array['BookingItem']['Hotel']['reception_tel_number']; ?></td>                                                 
                                </tr>
                                <tr>
                                    <td>Emergency Telephone</td>
                                    <td><?php echo $data_array['BookingItem']['Hotel']['reservation_tel_number']; ?></td>                                                 
                                </tr>
                                <tr>
                                    <td>City</td>
                                    <td><?php echo $data_array['BookingItem']['Hotel']['City']['name']; ?></td>                                                 
                                </tr>
                                <tr>
                                    <td>State</td>
                                    <td><?php echo $data_array['BookingItem']['Hotel']['State']['name']; ?></td>                                                 
                                </tr>
                                <tr>
                                    <td>Country</td>
                                    <td><?php echo $data_array['BookingItem']['Hotel']['Country']['name']; ?></td>                                                 
                                </tr>
                                <tr>
                                    <td>Location</td>
                                    <td><?php echo $data_array['BookingItem']['Hotel']['location']; ?></td>                                                 
                                </tr>
                                <tr>
                                    <td>Address1</td>
                                    <td><?php echo $data_array['BookingItem']['Hotel']['address1']; ?></td>                                                 
                                </tr>
                                <tr>
                                    <td>Address2</td>
                                    <td><?php echo $data_array['BookingItem']['Hotel']['address2']; ?></td>                                                 
                                </tr>
                                <tr>
                                    <td>Telephone</td>
                                    <td><?php echo $data_array['BookingItem']['Hotel']['telephone']; ?></td>                                                 
                                </tr>
                                <tr>
                                    <td>Fax</td>
                                    <td><?php echo $data_array['BookingItem']['Hotel']['fax']; ?></td>                                                 
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td><?php echo $data_array['BookingItem']['Hotel']['email']; ?></td>                                                 
                                </tr>
                                <tr>
                                    <td>Postcode</td>
                                    <td><?php echo $data_array['BookingItem']['Hotel']['post_code']; ?></td>                                                 
                                </tr>
                                <tr>
                                    <td>Website</td>
                                    <td><?php echo $data_array['BookingItem']['Hotel']['website']; ?></td>                                                 
                                </tr> 
                                <tr>
                                    <td>Check in time</td>
                                    <td><?php echo $data_array['BookingItem']['Hotel']['checkin_time']; ?></td>                                                 
                                </tr>
                                <tr>
                                    <td>Checkout in time</td>
                                    <td><?php echo $data_array['BookingItem']['Hotel']['checkout_time']; ?></td>                                                 
                                </tr>
                                <tr>
                                    <th colspan="2">&nbsp;</th>
                                </tr>
                                <tr>
                                    <th colspan="2" style="background: #d2d6de none repeat scroll 0 0;color: #FFF;">Rooms</th>
                                </tr>

                                <?php foreach ($data_array['BookingItem']['BookingItemRoom'] as $data) { ?>
                                    <tr>
                                        <th>Room name</th>
                                        <th><?php echo $data['room_name']; ?></th>
                                    </tr>
                                    <tr>
                                        <td>Room type</td>
                                        <td><?php echo $data['room_type_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Price plan</td>
                                        <td><?php echo $data['price_plan_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Item status</td>
                                        <td><?php echo $this->Custom->item_status($data_array['BookingItem']['item_status']); ?></td>                                          
                                    </tr>
                                    <tr>
                                        <td colspan="2"> &nbsp;</td> 
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php
                        $passenger_detail = $this->Custom->get_passenger_by_room($data_array['Booking']['id']);
                        ?>

                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td colspan="2">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Passenger Detail</h3>
                                        </div><!-- /.box-header -->
                                    </td>
                                </tr>
                                <?php foreach ($passenger_detail as $ps_detail) { ?>
                                    <tr>
                                        <th colspan="2"><?php echo $ps_detail['a']['room_number']; ?></th> 
                                    </tr> 
                                    <tr>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Dob</th>
                                    </tr>
                                    <?php
                                    foreach ($ps_detail[0] as $k => $t) {
                                        $room = json_decode($t, true);
                                        foreach ($room as $key => $value) {
                                            ?>
                                            <tr>
                                                <td><?php echo $value['name']; ?></td>
                                                <td><?php echo $this->Custom->pass_type($value['pass_type']); ?></td>
                                                <td><?php echo $value['dob']; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                <?php } ?>
                            </tbody>
                        </table> 
                    </td>
                </tr>
                <tr>
                    <td>
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td colspan="2">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Special request</h3>
                                        </div><!-- /.box-header -->
                                    </td>
                                </tr>
                                <tr>
                                    <td>Name</td>
                                    <td><?php echo $data_array['Booking']['special_request']; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>











