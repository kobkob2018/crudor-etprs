<div class="leftbar-top-holder"></div>
<div class="grab-content form-banner-holder" data-grab="form-img">
</div>

<?php $this->call_module('biz_form','fetch_form',$this->data); ?>

<div class="leftbar-mid-holder"></div>
<div class="supplier-cubes-wrap">
    <?php $this->call_module('supplier_cubes','add_leftbar_cubes',$this->data); ?>
</div>


<div class="supplier-cubes-wrap">
    <?php $this->call_module('net_banners','add_leftbar_banners',$this->data); ?>
</div>
<div class="leftbar-bottom-holder"></div>