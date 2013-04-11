<div class="page-header">
    <h1><?=__('Site Usage Statistics')?></h1>   
</div>


<div class="row">

    <div class="span9">

        <table class="table table-bordered table-condensed">
            <thead>
                <tr>
                    <th></th>
                    <th><?=__('Yesterday')?></th>
                    <th><?=__('Last week')?></th>
                    <th><?=__('Last month')?></th>
                    <th><?=__('Total')?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><b><?=__('Ads')?></b></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td><b><?=__('Views')?></b></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <hr>
        <h2><?=__('Charts')?></h2>

        <form id="edit-profile" class="form-inline" method="post" action="">
            <fieldset>
                <?=__('From')?>
                <input  type="text" class="span2" size="16"
                        id="from_date" name="from_date"  value="<?=$from_date?>"  
                        data-date="<?=$from_date?>" data-date-format="yyyy-mm-dd">
                <?=__('To')?>
                <input  type="text" class="span2" size="16"
                        id="to_date" name="to_date"  value="<?=$to_date?>"  
                        data-date="<?=$to_date?>" data-date-format="yyyy-mm-dd">

            <button type="submit" class="btn btn-primary"><?=__('Filter')?></button> 
            
            </fieldset>
        </form>


        
    </div> <!-- /.span12 -->
    
</div> <!-- /.row -->



<?=Chart::column($ads_daily,array('title'=>__('Visits Daily'),
                                    'height'=>400,
                                    'width'=>800))?>      

<?=Chart::column($visits_daily,array('title'=>__('Visits Daily'),
                                    'height'=>400,
                                    'width'=>800))?>                                    