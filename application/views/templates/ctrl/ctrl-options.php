<?php  foreach ($options as $key => $value): ?>
  <li class="collection-item collection-options">
    <?php  if ($value['title'] != ""): ?>
      <p style="line-height:1rem;margin: 5px 0 15px 0;font-weight: bolder;"><?php echo $value['title']?></p>
    <?php  endif ?>
    <span class="title"><?php echo $value['name']?></span>
    <div class="switch secondary-content">
     <label>
     <input id="<?php echo $key?>" class="<?php echo $option_class?>"
        <?php  $checked = (substr($value['name'],0,3)=="Tod"?"":$value['name']);
          echo ($field==$checked?'checked':'')?>
        type="checkbox"><span class="lever"></span>
     </label>
     </div>
  </li>
<?php  endforeach ?>
