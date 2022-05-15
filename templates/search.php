<main>
    <nav class="nav">
      <ul class="nav__list container">
        <?php foreach ($categories as $category) :?>
            <li class="nav__item <?=htmlspecialchars($category['code'])?>">
                <a href="/category.php?id=<?=$category['id']?>"><?=htmlspecialchars($category['name'])?></a>
            </li>
        <?php endforeach;?>
      </ul>
    </nav>
    <div class="container">
      <section class="lots">
        <h2>Результаты поиска по запросу «<span><?=$_GET['search']?></span>»</h2>
        <?php if ($lots === 'Ничего не найдено по вашему запросу'):?>
        <h3>«Ничего не найдено по вашему запросу»</h3>
        <?php else:?>
        <ul class="lots__list">
        <?php foreach ($lots as $lot) :?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?=htmlspecialchars($lot['img'])?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?=htmlspecialchars($lot['category'])?></span>
                    <h3 class="lot__title">
                        <a class="text-link" href="/lot.php?id=<?=htmlspecialchars($lot['id'])?>"><?=htmlspecialchars($lot['name'])?></a>
                    </h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?=formatPrice(htmlspecialchars($lot['begin_price']) )?></span>
                        </div>
                        <?php
                        $dataRange = get_dt_range($lot['date_completion'],date('Y-m-d H:i:s'));
                        ?>
                        <div class="lot__timer timer <?if ($dataRange['hour']<1){echo 'timer--finishing';}?>">
                            <?=$dataRange['hour']?>:<?=$dataRange['minute']?>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach;?>
        </ul>
      <?php endif;?>
      </section>
      <?php if($countPage>1):?>
      <ul class="pagination-list">
        <?php if ($page>1):?>
        <li class="pagination-item pagination-item-prev"><a href="?search=<?=$_GET['search']?>&find=Найти&page=<?=$page-1;?>">Назад</a></li>
        <?endif;?>

        <?php for ($i = 1; $i<=$countPage; $i++):?>
          <li class="pagination-item <?php if($i == $page){echo 'pagination-item-active';}?>"><a href="?search=<?=$_GET['search']?>&find=Найти&page=<?=$i?>"><?=$i?></a></li>
        <?php endfor;?>

        <?php if ( $page != $countPage ) :?>
        <li class="pagination-item pagination-item-next"><a href="?search=<?=$_GET['search']?>&find=Найти&page=<?=$page+1;?>">Вперед</a></li>
        <?php endif;?>
      </ul>
      <?php endif;?>
    </div>
  </main>