<?php get_header(); ?>



<div class="home">

    <header class="text-center">
        <div class="scilife-logo">
            <img class="scilife-logo-img" src="<?php echo get_stylesheet_directory_uri(); ?>/img/scilifelab-logo-bp.svg" title="Science for Life Laboratory" alt="SciLifeLab logo" />
        </div>
    </header>

    <section>
        <div class="showcase">
            <?php
            $gh_repos = array();
            $args = array( 'post_type' => 'projects', 'posts_per_page' => -1 );
            $loop = new WP_Query( $args );
            while ( $loop->have_posts() ) : $loop->the_post();
                $data = array();
                foreach(get_post_meta(get_the_ID()) as $key => $var){
                    if(substr($key, 0, 5) == 'wpcf-'){
                        $data[$key] = $var[0];
                    }
                    if($key == 'wpcf-github-url'){
                        $url_path = parse_url($var[0], PHP_URL_PATH);
                        $gh_repos[get_the_ID()] = trim($url_path, '/');
                    }
                }
                ?>
                <div class="case-wrapper">
                    <a href="<?php echo $data['wpcf-github-url']; ?>" alt="See the <?php the_title(); ?> code" class="case-action-wrapper">
                        <div id="gh-stars-<?php echo get_the_id(); ?>" class="case-action">
                            <div class="icon-star"></div>
                        </div>
                    </a>

                    <a class="case" href="<?php the_permalink(); ?>">
                    <div class="case-logo bg-yellow" style="background:<?php echo $data['wpcf-background-colour']; ?>">
                    <?php if(isset($data['wpcf-project-logo'])) { ?>
                        <img class="case-logo-img" src="<?php echo $data['wpcf-project-logo']; ?>" width="48" height="48" alt="<?php the_title(); ?>">
                    <?php } else if(isset($data['wpcf-project-icon'])) { ?>
                        <div class="case-logo-icon <?php echo $data['wpcf-project-icon']; ?>"></div>
                    <?php } ?>
                    </div>
                    <div class="case-intro">
                        <span class="case-intro-title"><?php the_title(); ?></span>
                        <span class="case-intro-text"><?php echo $data['wpcf-project-tagline-short']; ?></span>
                    </div>
                    </a>
                </div>
        <?php endwhile; ?>
        </div>
    </section>

    <?php if(!is_user_logged_in()){ ?>
    <footer>
        <p class="text-center"><a href="<?php echo wp_login_url( home_url() ); ?>" title="Login">Log in</a></p>
    </footer>
    <?php } ?>

</div>


<script>
jQuery(function() {
    <?php foreach($gh_repos as $id => $repo){ ?>
    jQuery.getJSON('https://api.github.com/repos/<?php echo $repo; ?>', function(data) {
        jQuery('#gh-stars-<?php echo $id; ?>').append(data.stargazers_count);
    });
    <?php } ?>
});
</script>


<?php get_footer(); ?>
