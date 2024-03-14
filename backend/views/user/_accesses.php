<?php
use common\components\AccessesComponent;

?>
<div class="accesses-list">
    <div class="card">
        <div class="card-header">
            Доступы к разделам
        </div>
        <div class="card-body">
            <?php if($accessesList = Yii::$app->accesses->getAccessesForUser($model->id)) : ?>
                <?php foreach($accessesList as $accessItem) : ?>
                    <?php if($accessItem['access_values']) : ?>
                        <div class="form-group">
                            <?= $accessItem['access_name'] ?>:
                            <ul>
                                <?php foreach($accessItem['access_values'] as $access_value) : ?>
                                    <?php
                                        $id = "type_{$accessItem['access_type']}_{$access_value['id']}";
                                        $checked = $access_value['checked'] ? ' checked="checked"' : '';
                                    ?>
                                    <li>
                                        <input id="<?= $id ?>" type="checkbox" name="User[sections_accesses][<?= $accessItem['access_type'] ?>][]" value="<?= $access_value['id'] ?>" <?= $checked ?>>
                                        <label for="<?= $id ?>"><?= $access_value['name'] ?></label>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php else : ?>
                        <?php
                            $checked = $accessItem['checked'] ? ' checked="checked"' : '';
                        ?>
                        <div class="form-group">
                            <input id="type_<?= $accessItem['access_type'] ?>" type="checkbox" name="User[sections_accesses][<?= $accessItem['access_type'] ?>]" value="1" <?= $checked ?>>
                            <label for="type_<?= $accessItem['access_type'] ?>"><?= $accessItem['access_name'] ?></label>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
