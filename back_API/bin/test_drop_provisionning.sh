#!/bin/sh

for i in $(seq 1 10);
do
  php bin/console app:sale-item-provisioning "drop-sale-item" "Cooki $i";
done

for i in $(seq 1 10);
do
  php bin/console app:sale-item-dark-mode-provisioning "drop-sale-item" "Dark Cooki $i";
done

for i in $(seq 1 10);
do
  php bin/console app:user-provisioning "drop-user" "user$i@example.com" "password";
done

for i in $(seq 1 10);
do
  php bin/console app:user-provisioning "drop-user" "dark-user$i@example.com" "password";
done
