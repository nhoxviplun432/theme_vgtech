<?php
namespace Vgtech\ThemeVgtech;

use Vgtech\ThemeVgtech\Hookable;
use Vgtech\ThemeVgtech\Providers\AssetsProvider;
use Vgtech\ThemeVgtech\Providers\ThemeSupportProvider;
use Vgtech\ThemeVgtech\Providers\WooProvider;
use Vgtech\ThemeVgtech\Providers\SwupProvider;

final class App
{
    /** @var array<Hookable> */
    private array $providers = [];

    public function __construct()
    {
        $this->providers = [
            new SwupProvider(),
            new ThemeSupportProvider(),
            new AssetsProvider(),
            class_exists('\WooCommerce') ? new WooProvider() : null,
        ];
        $this->providers = array_values(array_filter($this->providers));
    }

    public function boot(): void
    {
        foreach ($this->providers as $p) {
            if ($p instanceof Hookable) {
                $p->register();
            }
        }
    }
}

