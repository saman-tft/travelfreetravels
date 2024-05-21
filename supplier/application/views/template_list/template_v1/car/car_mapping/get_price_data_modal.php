
<div class="modal-content" style='width:900px;'>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Variable Price</h4>
    </div>
    <div class="modal-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-highlight">
                <thead>
                <th >Hours -></th>
                <?php for($i=2;$i<=24;$i+=2){ ?>
                    <th><?= $i ?> Hr</th>
                <?php } ?>
                </thead>
                <tbody>
                <?php if($type == 'wkday'){ ?>
                <tr>
                    <td >WeekDay Price(QAR)</td>
                    <?php for($i=2;$i<=24;$i+=2){ ?>
                        <td style='width:100px;'><?=$price_data['wk_day'][$i]?></td>
                    <?php } ?>

                </tr>
                <?php } if($type == 'wkend'){ ?>
                <tr >
                    <td >WeekEnd Price(QAR)</td>
                    <?php for($i=2;$i<=24;$i+=2){ ?>
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