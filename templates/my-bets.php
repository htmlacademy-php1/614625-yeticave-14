<main>
    <nav class="nav">
      <ul class="nav__list container">
        <?php foreach ($categories as $category) :?>
            <li class="nav__item">
                <a href="/category.php?id=<?=$category['id']?>"><?=htmlspecialchars($category['name'])?></a>
            </li>
        <?php endforeach;?>
      </ul>
    </nav>
    <section class="rates container">
      <h2>Мои ставки</h2>
      <table class="rates__list">
        <?php foreach ($bets as $bet):?>
        <tr class="rates__item">
          <td class="rates__info">
            <div class="rates__img">
              <img src="<?=$bet['img']?>" width="54" height="40" alt="<?=$bet['lots_name']?>">
            </div>
            <h3 class="rates__title"><a href="lot.html"><?=$bet['lots_name']?></a></h3>
          </td>
          <td class="rates__category"><?=$bet['category_name']?></td>
          <td class="rates__timer">
            <div class="timer timer--finishing">07:13:34</div>
          </td>
          <td class="rates__price">
            <?=formatPrice($bet['price'])?>
          </td>
          <td class="rates__time">
            <?=$bet['creation_time']?>
          </td>
        </tr>
        <?endforeach;?>
      </table>
    </section>
  </main>