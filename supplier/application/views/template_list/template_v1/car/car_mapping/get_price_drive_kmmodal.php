
<div class="modal-content" style='width:900px;'>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Drive Me Price(Km)</h4>
    </div>
    <div class="modal-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-highlight">
                <thead>
                <th >Km</th>
                <?php for($i=0;$i<9;$i+=1){ 
                    $miles=array('0-1000','1000-2000','2000-3000','3000-4000','4000-5000','5000-6000','6000-7000','7000-8000','8000-9000');?>
                    <th><?= $miles[$i]; ?> Km</th>
                <?php } ?>
                </thead>
                <tbody>
                <?php if($type == 'drive_mwkday'){ ?>
                <tr>
                    <td >WeekDay Price(AUD)</td>
                    <?php for($i=0;$i<9;$i+=1){ ?>
                        <td style='width:100px;'><?=$price_data['wk_day'][$i]?></td>
                    <?php } ?>

                </tr>
                <?php } if($type == 'drive_mwkend'){ ?>
                <tr >
                    <td >WeekEnd Price(AUD)</td>
                    <?php for($i=0;$i<9;$i+=1){ ?>
                        <td style='width:100px;'><?=$price_data['wk_end'][$i]?></td>
                    <?php } ?>

                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>