<div style="width:100%;overflow:auto;margin:10px 0px;" 
     class="table-responsive table-card" 
     id="load_diamond_gems_pearls">
    <table id="<?php echo $type;?>_add_data" style="font-size:12px;"  class="table table-borderless text-center table-nowrap align-middle mb-0">
    <thead class="table-active" >
    <tr>
                                                                <?php if($type=='diamonds') { ?>
                                                                <th style="width:12.5%;">Name</th>
                                                                <th  style="width:12.5%;">Cut</th>
                                                                <th style="width:12.5%;">Shape</th>
                                                                <th style="width:12.5%;">Color</th>
                                                                <th style="width:12.5%;">Clarity</th>
                                                                <th style="width:12.5%;">Pointers</th>
                                                                <th style="width:12.5%;">Sieve Size</th>
                                                                <th style="width:12.5%;">Rate</th>
                                                                <?php }  ?>
                                                                <?php if($type=='gemstones') { ?>
                                                                <th style="width:12.5%;">Name</th>
                                                                <th style="width:12.5%;">Type</th>
                                                                <th style="width:12.5%;">Cut</th>
                                                                <th style="width:12.5%;">Shape</th>
                                                                <th style="width:12.5%;">Quality</th>
                                                                <th style="width:12.5%;">Size</th>
                                                                <th style="width:12.5%;">Origin</th>
                                                                <th style="width:12.5%;">Rate</th>
                                                                <?php }  ?>
                                                                <?php if($type=='pearls') {
                                                                $width=100/7;    
                                                                    ?>
                                                                <th   style="width:12.5%;">Name</th>
                                                                <th   style="width:12.5%;">Type</th>
                                                                <th   style="width:12.5%;">Shape</th>
                                                                <th   style="width:12.5%;">Color</th>
                                                                <th   style="width:12.5%;">Size</th>
                                                                <th   style="width:12.5%;">Unit</th>
                                                                <th   style="width:12.5%;">Rate</th>                                                                
                                                                <?php }  ?>
                                                                <?php if($type=='dimensions') { 
                                                                    $width=100/3;    
                                                                    ?>
                                                                <th style="width:250px;">Name</th>
                                                                <th style="width:250px;">Value</th>
                                                                <th style="width:250px;">Unit</th>
                                                                <?php }  ?>
                                                            </tr>
</thead>
<tbody id="<?php echo $type;?>_add_data_res" >  
    <tr>
    <th style="width:12.5%;"></th>
    <th style="width:12.5%;"></th>
    <th style="width:12.5%;"></th>
    <th style="width:12.5%;"></th>
    <th style="width:12.5%;"></th>
    <th style="width:12.5%;"></th>
    <th style="width:12.5%;"></th>
    <th style="width:12.5%;"></th>
    </tr>
</tbody>  
</table>
</div>    