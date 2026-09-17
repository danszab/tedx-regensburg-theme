<?php
/**
 * Hero Section Template Part (Figma: Homepage - Hero alternative 2, node 2194:1939)
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$bg_image     = tedx_mod( 'tedx_hero_background_image', '' );
$poster_image = tedx_mod( 'tedx_hero_poster_image', '' );
?>
<style>
    .tedx-hero-section {
        height: 100vh;
        min-height: 100vh;
        width: 100%;
        position: relative;
        overflow: hidden;
        background-color: #000;
        color: #fff;
        display: flex;
        align-items: center;
    }
    .tedx-hero-bg {
        position: absolute;
        inset: 0;
        z-index: 0;
    }
    .tedx-hero-bg img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: blur(3.5px);
        transform: scale(1.05);
        animation: heroBgPan 3s cubic-bezier(0.1, 1, 0, 1) forwards;
    }
    .tedx-hero-bg-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to bottom, rgba(0,0,0,0.4), rgba(0,0,0,0.7));
    }
    .tedx-hero-container {
        position: relative;
        z-index: 10;
        width: 100%;
        margin: 0 auto;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
    }
    .tedx-hero-typography {
        display: flex;
        flex-direction: column;
        z-index: 20;
    }
    .tedx-hero-welcome {
        font-family: 'Helvetica_Neue_LT_Pro', sans-serif;
        font-weight: 500;
        font-size: 40px;
        line-height: 1;
        letter-spacing: -2px;
        margin-bottom: 24px;
    }
    .tedx-hero-logo-img {
        width: 100%;
        max-width: 600px;
        height: auto;
        object-fit: contain;
        display: block;
    }
    
    /* Overrides for the fallback markup if no image is uploaded */
    .tedx-hero-logo .flex {
        flex-direction: column;
        align-items: flex-start;
        gap: 0;
    }
    .tedx-hero-logo .flex span:first-child {
        font-family: 'Helvetica_Neue_LT_Pro', sans-serif !important;
        font-weight: 900 !important;
        font-size: 160px !important;
        line-height: 0.8 !important;
        letter-spacing: -3.2px !important;
    }
    .tedx-hero-logo .flex span:first-child sup {
        font-size: 160px !important;
    }
    .tedx-hero-logo .flex span:last-child {
        font-family: 'Helvetica_Neue_LT_Pro', sans-serif !important;
        font-weight: 500 !important;
        font-size: 64px !important;
        line-height: 1 !important;
        letter-spacing: -1.28px !important;
        margin-left: 0 !important;
        margin-top: 24px !important;
    }
    .tedx-hero-poster-wrapper {
        height: 75vh;
        max-height: 774px;
        aspect-ratio: 616 / 774;
        border: 2px solid rgba(255, 255, 255, 0.4);
        border-radius: 48px;
        overflow: hidden;
        flex-shrink: 0;
        position: relative;
        z-index: 20;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
    .tedx-hero-poster-wrapper img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    @media (max-width: 1024px) {
        .tedx-hero-container {
            flex-direction: column;
            justify-content: center;
            gap: 24px;
            padding-top: 64px;
            padding-bottom: 64px;
        }
        .tedx-hero-typography {
            align-items: center;
            text-align: center;
        }
        .tedx-hero-welcome { font-size: 32px; }
        
        .tedx-hero-logo .flex { align-items: center; }
        .tedx-hero-logo .flex span:first-child { font-size: 100px !important; }
        .tedx-hero-logo .flex span:first-child sup { font-size: 100px !important; }
        .tedx-hero-logo .flex span:last-child { font-size: 48px !important; }
        
        .tedx-hero-poster-wrapper {
            height: 50vh;
            border-radius: 32px;
        }
    }
    @media (max-width: 640px) {
        .tedx-hero-welcome { font-size: 24px; margin-bottom: 16px; }
        
        .tedx-hero-logo .flex span:first-child { font-size: 72px !important; }
        .tedx-hero-logo .flex span:first-child sup { font-size: 72px !important; }
        .tedx-hero-logo .flex span:last-child { font-size: 36px !important; }
        
        .tedx-hero-poster-wrapper {
            height: 40vh;
        }
    }

    @keyframes heroBgPan {
        0% {
            transform: scale(1.15) translateX(3%);
        }
        100% {
            transform: scale(1.05) translateX(0%);
        }
    }
</style>

<section id="hero" class="tedx-hero-section">

    <!-- Background Layer with Blur and Gradient overlay -->
    <div class="tedx-hero-bg">
        <?php if ( $bg_image ) : ?>
            <img src="<?php echo esc_url( $bg_image ); ?>" alt="" />
        <?php endif; ?>
        <div class="tedx-hero-bg-overlay"></div>
    </div>

    <!-- Content Container -->
    <div class="tedx-hero-container max-w-figma-wide px-4 md:px-8 lg:px-12">
        
        <!-- Left Side: Typography -->
        <div class="tedx-hero-typography">
            <p class="tedx-hero-welcome">
                <?php esc_html_e( 'Welcome to', 'tedx-regensburg' ); ?>
            </p>
            
            <div class="tedx-hero-logo">
                <?php 
                if ( function_exists('tedx_regensburg_logo') ) {
                    tedx_regensburg_logo('tedx-hero-logo-img', false);
                }
                ?>
            </div>
        </div>

        <!-- Right Side: Event Poster -->
        <div class="tedx-hero-poster-wrapper">
            <?php if ( $poster_image ) : ?>
                <img src="<?php echo esc_url( $poster_image ); ?>" alt="Event Poster" />
            <?php else : ?>
                <!-- Placeholder if no image set -->
                <div style="position: absolute; inset: 0; background: #222; display: flex; align-items: center; justify-content: center;">
                    <span style="color: rgba(255,255,255,0.5); font-size: 14px;">Poster Image (Customizer)</span>
                </div>
            <?php endif; ?>
        </div>

    </div>

</section>
