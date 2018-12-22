<? foreach ($options as $key => $value): ?>
  <li class="collection-item collection-options">
    <? if ($value['title'] != ""): ?>
      <p style="line-height:1rem;margin: 5px 0 15px 0;font-weight: bolder;"><?=$value['title']?></p>
    <? endif ?>
    <span class="title"><?=$value['name']?></span>
    <div class="switch secondary-content">
     <label>
     <input id="<?=$key?>" class="<?=$option_class?>"
        <? $checked = (substr($value['name'],0,3)=="Tod"?"":$value['name']);
          echo ($field==$checked?'checked':'')?>
        type="checkbox"><span class="lever"></span>
     </label>
     </div>
  </li>
<? endforeach ?>
