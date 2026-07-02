{ pkgs, lib, config, ... }:
let
  php = pkgs.php84.withExtensions ({ enabled, all }: enabled ++ [
    all.pdo_pgsql
  ]);
in {
  packages = [
    php
  ] ++ lib.optionals (!config.container.isBuilding) [
    php.packages.composer
  ];

  enterShell = ''
    set +x
    set -a; [ -f .env ] && source .env; set +a
    export PATH="$PWD/vendor/bin:$PATH"
  '';
}
