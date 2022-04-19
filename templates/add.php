<!--
Проверить, что отправлена форма.
Убедиться, что заполнены все поля.
Выполнить все проверки.
Если есть ошибки заполнения формы, то сохранить их в отдельном массиве.
Если ошибок нет, то сохранить новый лот в таблице лотов, а файл изображения перенести в публичную директорию и сохранить ссылку.
При успешном сохранении формы, переадресовывать пользователя на страницу просмотра лота.

Список проверок

Проверка изображения

Обязательно проверять MIME-тип загруженного файла;
Допустимые форматы файлов: jpg, jpeg, png;
Для проверки сравнивать MIME-тип файла со значением «image/png», «image/jpeg»;
Чтобы определить MIME-тип файла, использовать функцию mime_content_type.

Проверка начальной цены

Содержимое поля «начальная цена» должно быть числом больше нуля.
Проверка даты завершения

Содержимое поля «дата завершения» должно быть датой в формате «ГГГГ-ММ-ДД»;
Проверять, что указанная дата больше текущей даты, хотя бы на один день.
Проверка шага ставки

Содержимое поля «шаг ставки» должно быть целым числом больше ноля.



список проверок
Проверка изображения

Обязательно проверять MIME-тип загруженного файла;
Допустимые форматы файлов: jpg, jpeg, png;
Для проверки сравнивать MIME-тип файла со значением «image/png», «image/jpeg»;
Чтобы определить MIME-тип файла, использовать функцию mime_content_type.

Проверка начальной цены
Содержимое поля «начальная цена» должно быть числом больше нуля.

Проверка даты завершения

Содержимое поля «дата завершения» должно быть датой в формате «ГГГГ-ММ-ДД»;
Проверять, что указанная дата больше текущей даты, хотя бы на один день.

Проверка шага ставки
Содержимое поля «шаг ставки» должно быть целым числом больше ноля.-->
    <form class="form form--add-lot container <?php if(isset($errors)){echo 'form--invalid';}?>" action="add.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
        <h2>Добавление лота</h2>
        <div class="form__container-two">
            <div class="form__item <?php if (isset($errors['lot-name'])){if (!$errors['lot-name']){echo 'form__item--invalid';}}?>">
                <label for="lot-name">Наименование <sup>*</sup></label>
                <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота"
                       value="<?=$lotFormData['lot-name'];?>">
                <?php if (isset($errors['lot-name'])):
                if (!$errors['lot-name']):?>
                    <span class="form__error">Введите наименование лота</span>
                <?endif;
                    endif;?>
            </div>
            <div class="form__item <?php if (isset($errors['category'])){if (!$errors['category']){echo 'form__item--invalid';}}?>">
                <label for="category">Категория <sup>*</sup></label>
                <select id="category" name="category">
                    <option>Выберите категорию</option>
                    <?php foreach ($categories as $category) :
                    echo $lotFormData['category'];?>
                        <option value="<?=$category['id']?>" <?php if ($category['id'] === $lotFormData['category']) {echo "selected";} ?>><?=htmlspecialchars($category['name'])?></option>
                    <?php endforeach;?>
                </select>
                <span class="form__error">Выберите категорию</span>
            </div>
        </div>
        <div class="form__item form__item--wide <?php if (isset($errors['message'])){if (!$errors['message']){echo 'form__item--invalid';}}?>">
            <label for="message">Описание <sup>*</sup></label>
            <textarea id="message" name="message"
                      placeholder="Напишите описание лота"><?=$lotFormData['message'];?></textarea>
            <span class="form__error">Напишите описание лота</span>
        </div>
        <div class="form__item form__item--file">
            <label>Изображение <sup>*</sup></label>
            <div class="form__input-file">
                <input id="lot-img" name="lot-img" class="visually-hidden" type="file"  value="">
                <label for="lot-img">
                    Добавить
                </label>
            </div>
        </div>
        <div class="form__container-three">
            <div class="form__item form__item--small <?php if (isset($errors['lot-rate'])){if (!$errors['lot-rate']){echo 'form__item--invalid';}}?>">
                <label for="lot-rate">Начальная цена <sup>*</sup></label>
                <input id="lot-rate" type="text" name="lot-rate" placeholder="0" value="<?=$lotFormData['lot-rate'];?>">
                <span class="form__error">Введите начальную цену</span>
            </div>
            <div class="form__item form__item--small <?php if (isset($errors['lot-step'])){if (!$errors['lot-step']){echo 'form__item--invalid';}}?>">
                <label for="lot-step">Шаг ставки <sup>*</sup></label>
                <input id="lot-step" type="text" name="lot-step" placeholder="0" value="<?=$lotFormData['lot-step'];?>">
                <span class="form__error">Введите шаг ставки</span>
            </div>
            <div class="form__item <?php if (isset($errors['lot-date'])){if (!$errors['lot-date']){echo 'form__item--invalid';}}?>">
                <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
                <input class="form__input-date" id="lot-date" type="text" name="lot-date" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?=$lotFormData['lot-date'];?>">
                <span class="form__error">Введите дату завершения торгов</span>
            </div>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Добавить лот</button>
    </form>
