<form class="form container <?php if(count($errors)>0){echo 'form--invalid';}?>" action="/sign-up.php" method="post" autocomplete="off">
    <h2>Регистрация нового аккаунта</h2>
    <div class="form__item <?php if(isset($errors['email'])){if($errors['email']){echo 'form__item--invalid';}}?>">
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?=$userFormData['email'];?>">
        <span class="form__error"><?php if(isset($errors['email'])){if($errors['email']){echo $errors['email'];}}?></span>
    </div>
    <div class="form__item <?php if(isset($errors['password'])){if($errors['password']){echo 'form__item--invalid';}}?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль">
        <span class="form__error"><?php if(isset($errors['password'])){if($errors['password']){echo $errors['password'];}}?></span>
    </div>
    <div class="form__item <?php if(isset($errors['name'])){if($errors['name']){echo 'form__item--invalid';}}?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<?=$userFormData['name'];?>">
        <span class="form__error"><?php if(isset($errors['name'])){if($errors['name']){echo $errors['name'];}}?></span>
    </div>
    <div class="form__item <?php if(isset($errors['contact'])){if($errors['contact']){echo 'form__item--invalid';}}?>">
        <label for="contact">Контактные данные <sup>*</sup></label>
        <textarea id="contact" name="contact" placeholder="Напишите как с вами связаться"><?=$userFormData['contact'];?></textarea>
        <span class="form__error"><?php if(isset($errors['contact'])){if($errors['contact']){echo $errors['contact'];}}?></span>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="#">Уже есть аккаунт</a>
</form>
