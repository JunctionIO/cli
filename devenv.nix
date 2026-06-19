{ pkgs, lib, config, ... }:
let
  php = pkgs.php84.withExtensions ({ enabled, all }: enabled ++ [
    # Add PDO extensions here:
    # all.pdo_pgsql
    # all.pdo_mysql
  ]);
in {
  packages = [
    php
  ] ++ lib.optionals (!config.container.isBuilding) [
    php.packages.composer
  ];

  # Add services here:
  # services.postgres = {
  #   enable = true;
  #   listen_addresses = "127.0.0.1";
  # };
  # services.redis.enable = true;

  enterShell = ''
    set +x
    set -a; [ -f .env ] && source .env; set +a
    export PATH="$PWD/vendor/bin:$PATH"
  '';
}
