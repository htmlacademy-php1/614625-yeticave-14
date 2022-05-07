<form class="form container <?php if(count($errors)>0){echo 'form--invalid';}?>" action="/login.php" method="post">
    <h2>Вход</h2>
    <div class="form__item <?php if(isset($errors['email'])){if($errors['email']){echo 'form__item--invalid';}}?>">
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?=$userLoginData['email'];?>">
        <span class="form__error"><?php if(isset($errors['email'])){if($errors['email']){echo $errors['email'];}}?></span>
    </div>
    <div class="form__item <?php if(isset($errors['password'])){if($errors['password']){echo 'form__item--invalid';}}?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль">
        <span class="form__error"><?php if(isset($errors['password'])){if($errors['password']){echo $errors['password'];}}?></span>
    </div>
    <button type="submit" class="button">Войти</button>
</form>

