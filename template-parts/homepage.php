<main class="container py-4">
    <section class="row">
        <div class="col-md-6 center-vertically">
            <h1 class="title">مدونة ميسان للفضاء</h1>
            <p class="sub-title">كل ما يخص الفضاء و الكون وتاريخه وتقنياته
            شاركنا رحلة التعلم !</p>
        </div>
        <div class="meissa-hide-md col-md-6"
            <?php meissa_bg_img(THEME_URI.'/meissa-assets/James-Web-Telescope.webp','400px') ?>>
        </div>
    </section>
    <section class="row p-4" id="final-note">
        <style>
            #final-note {
                border: 2px dashed var(--clr-pri);
                background: var(--clr-bg-lt);
            }
            #final-note h2 {
                color: var(--clr-pri);
                font-size: var(--fs-ter);
            }
        </style>
        
        <div class="col-md-6 py-3"> 
            
            <h2>زوار و قراء مدونة ميسان الكرام</h2>
            <p>
                توقف النشر في المدونة في تاريخ يناير 2025, بسبب ظروف خارجة عن إرادتنا 🙏
            </p>
            <p>يبقى المحتوى هنا متاحًا للفضوليين والمهتمين.</p>
            <br>
            <h2>شكرا خاص لفريق المدونة الكفؤ 🧡</h2>
            <p><strong><a href="https://abdoo.me">عبد اللطيف الميهوب</a></strong>: المدير, الممول و مسؤول التقنية في المدونة. </p>
            <p><strong><a href="https://meissa.space/ali-alkhatib/">علي الخطيب</a></strong>: رئيس التحرير ومدير المحتوى.</p>
        </div>

        <div class="col-md-6 py-3" style="direction: ltr; text-align: left;">
            <h2>Dear Visitors and Readers of Meissa Blog</h2>
            <p>
                Publishing on the blog stopped in January 2025, due to circumstances beyond our control 🙏
            </p>
            <p>The content remains available here for curious minds and enthusiasts.</p>
            <br>
            <h2>Special Thanks to Our Dedicated Team 🧡</h2>
            <p><strong><a href="https://abdoo.me">Abdoo</a></strong>: Blog Manager, Funder, and Technical Lead.</p>
            <p><strong><a href="https://meissa.space/ali-alkhatib/">Ali Al-Khatib</a></strong>: Editor-in-Chief and Content Director.</p>

        </div>
    </section>
    <section class="row">
        <div class="col-md-6 center-vertically">
            <h2 class="h1">مقالات مختارة</h2>
            <div class="row">
                <? $featured_posts = meissa_get_featured_posts(4); ?>
                <? while (  $featured_posts->have_posts() ):  $featured_posts->the_post();?>
                    <article class="col-12 pb-3">
                        <? get_template_part('template-parts/loop') ?>
                    </article>
                <? endwhile ?>
            </div>
        </div>
        <div id="Floating-Astro" class="meissa-hide-md col-md-6"
            <? meissa_bg_img(THEME_URI.'/meissa-assets/Floating-Astro.webp','580px') ?>>
        </div>
    </section>
    <section class="row">
        <h2 class="col-12 h1">الأكثر قراءة</h2>
        <? $most_read_posts = meissa_get_most_read_posts(); ?>
        <? while (  $most_read_posts->have_posts() ):  $most_read_posts->the_post();?>
            <article class="col-md-6 pb-3">
                <? get_template_part('template-parts/loop') ?>
            </article>
        <? endwhile ?>
        <? wp_reset_postdata() ?>
    </section>
    <section class="row mt-4">
        <h2 class="col-12 h1">أٌضيف مؤخراً</h2>
        <? $latest_posts = meissa_get_latest_posts(); ?>
        <? while (  $latest_posts->have_posts() ):  $latest_posts->the_post();?>
            <article class="col-md-6 pb-3">
                <? get_template_part('template-parts/loop') ?>
            </article>
        <? endwhile ?>
        <? wp_reset_postdata() ?>
    </section>
</main>