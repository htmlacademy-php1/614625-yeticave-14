<form class="form form--add-lot container <?php if (isset($errors)) {
    echo 'form--invalid';
} ?>" action="add.php" method="post" enctype="multipart/form-data">
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <div class="form__item <?php if (isset($errors['name'])) {if ($errors['name']) {echo 'form__item--invalid';}} ?>">
            <label for="name">Наименование <sup>*</sup></label>
            <input id="name" type="text" name="name" placeholder="Введите наименование лота"
                   value="<?= $lotFormData['name']; ?>">
            <?php if (isset($errors['name'])):
                if ($errors['name']):?>
                    <span class="form__error"><?=$errors['name'];?></span>
                <?endif;
            endif; ?>
        </div>
        <div class="form__item <?php if (isset($errors['category'])) {
            if ($errors['category']) {
                echo 'form__item--invalid';
            }
        } ?>">
            <label for="category">Категория <sup>*</sup></label>
            <select id="category" name="category">
                <option>Выберите категорию</option>
                <?php foreach ($categories as $category) :?>
                    <option value="<?=$category['id']?>" <?php if ($category['id'] === $lotFormData['category']) {
                        echo "selected";
                    } ?>><?= htmlspecialchars($category['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <span class="form__error"><?=$errors['category']?></span>
        </div>
    </div>
    <div class="form__item form__item--wide <?php if (isset($errors['description'])) {
        if ($errors['description']) {
            echo 'form__item--invalid';
        }
    } ?>">
        <label for="description">Описание <sup>*</sup></label>
        <textarea id="description" name="description"
                  placeholder="Напишите описание лота"><?= $lotFormData['description']; ?></textarea>
        <span class="form__error"><?=$errors['description']?></span>
    </div>
    <div class="form__item form__item--file <?php if (isset($errors['img'])) {
        if ($errors['img']) {
            echo 'form__item--invalid';
        }
    } ?>">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
            <input id="img" name="img" class="visually-hidden" type="file" value="">
            <label for="img">
                Добавить
            </label>
            <span class="form__error"><?=$errors['img']?></span>
        </div>
    </div>
    <div class="form__container-three">
        <div class="form__item form__item--small <?php if (isset($errors['begin_price'])) {
            if ($errors['begin_price']) {
                echo 'form__item--invalid';
            }
        } ?>">
            <label for="begin_price">Начальная цена <sup>*</sup></label>
            <input id="begin_price" type="text" name="begin_price" placeholder="0"
                   value="<?= $lotFormData['begin_price']; ?>">
            <span class="form__error"><?=$errors['begin_price']?></span>
        </div>
        <div class="form__item form__item--small <?php if (isset($errors['bid_step'])) {
            if ($errors['bid_step']) {
                echo 'form__item--invalid';
            }
        } ?>">
            <label for="bid_step">Шаг ставки <sup>*</sup></label>
            <input id="bid_step" type="text" name="bid_step" placeholder="0" value="<?= $lotFormData['bid_step']; ?>">
            <span class="form__error"><?=$errors['bid_step']?></span>
        </div>
        <div class="form__item <?php if (isset($errors['date_completion'])) {
            if ($errors['date_completion']) {
                echo 'form__item--invalid';
            }
        } ?>">
            <label for="date_completion">Дата окончания торгов <sup>*</sup></label>
            <input class="form__input-date" id="date_completion" type="text" name="date_completion" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?=$lotFormData['date_completion'];?>">
            <span class="form__error"><?=$errors['date_completion']?></span>
        </div>
    </div>
    <span class="form__error <?php if (count($errors)>0){echo 'form__error--bottom';}?>">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Добавить лот</button>
</form>
