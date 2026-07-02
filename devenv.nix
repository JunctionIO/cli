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

  containers.cli = {
    name = "cli";
    entrypoint = [ "php" "/app/bin/junction" ];
    copyToRoot = pkgs.buildEnv {
      name = "cli-app";
      paths = [
        (pkgs.runCommand "app" { } ''
          mkdir -p $out/app
          cp -r ${./src} $out/app/src
          cp -r ${./bin} $out/app/bin
          cp -r ${./migrations} $out/app/migrations
          cp -r ${./vendor} $out/app/vendor
        '')
      ];
    };
  };

  enterShell = ''
    set +x
    set -a; [ -f .env ] && source .env; set +a
    export PATH="$PWD/vendor/bin:$PATH"
  '';
}
