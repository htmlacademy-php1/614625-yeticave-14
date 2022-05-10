<section class="lot-item container">
    <h2>403 Доступ запрещен</h2>
    <?php if(!isset($_SESSION['user_id'])):?>
    <p>Данная страница недоступна неавторивованным пользователям.</p>
    <?php else:?>
    <p>Данная страница недоступна авторивованным пользователям.</p>    
    <?php endif;?>
</section>
