<main>
    <nav class="nav">
      <ul class="nav__list container">
        <?php foreach ($categories as $category) :?>
            <li class="nav__item <?php if(isset($_GET['id'])){if($category['id']==$_GET['id']){echo 'nav__item--current';}}?>">
                <a href="/category.php?id=<?=$category['id']?>"><?=htmlspecialchars($category['name'])?></a>
            </li>
        <?php endforeach;?>
      </ul>
    </nav>
    <div class="container">
      <section class="lots">
        <h2>Все лоты в категории <span>«<?=$nameCategory?>»</span></h2>
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
                        <?php $dataRange = get_dt_range($lot['date_completion'],date('Y-m-d H:i:s'));?>
                        <div class="lot__timer timer <?if ($dataRange['hour']<1){echo 'timer--finishing';}?>">
                            <?=sprintf("%02d", $dataRange['hour'])?>:<?= sprintf("%02d", $dataRange['minute'])?>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach;?>
        </ul>
      </section>
      <?php if($countPage>1):?>
      <ul class="pagination-list">
        <?php if ($page>1):?>
        <li class="pagination-item pagination-item-prev"><a href="?id=<?=$_GET['id']?>&page=<?=$page-1;?>">Назад</a></li>
        <?php endif;?>

        <?php for ($i = 1; $i<=$countPage; $i++):?>
          <li class="pagination-item <?php if($i == $page){echo 'pagination-item-active';}?>"><a href="?id=<?=$_GET['id']?>&page=<?=$i?>"><?=$i?></a></li>
        <?php endfor;?>

        <?php if ( $page != $countPage ) :?>
        <li class="pagination-item pagination-item-next"><a href="?id=<?=$_GET['id']?>&page=<?=$page+1;?>">Вперед</a></li>
        <?php endif;?>
      </ul>
      <?php endif;?>
    </div>
  </main>