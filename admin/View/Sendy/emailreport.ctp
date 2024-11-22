<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card card-default"> 
            <!-- /.card-heading -->
            <div class="card-body"> 
                <div class="dataTable_wrapper">
                    <?php 
                  $c = curl_init(SENDYURL.'reportcustom?i=1&c='.$champId.'&userId='.$userId.'&frm=admin');
                    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
                    $html = curl_exec($c);
                    if (curl_error($c)){
                        die(curl_error($c));
                    }
                    $status = curl_getinfo($c, CURLINFO_HTTP_CODE);
                    curl_close($c);
                    echo $html;
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>