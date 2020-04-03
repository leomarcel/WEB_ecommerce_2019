#!/bin/sh

for i in $(seq 1 10);
do
  php bin/console app:sale-item-provisioning "add-sale-item" "Cooki $i" "Ils sont très bon" "15";
done

for i in $(seq 1 10);
do
  php bin/console app:sale-item-dark-mode-provisioning "add-sale-item" "Dark Cooki $i" "Ils sont près pour le 7ème ciel" "15";
done

for i in $(seq 1 10);
do
  php bin/console app:user-provisioning "add-user" "user$i@example.com" "password" --name="User $i" --darkm=false;
done

for i in $(seq 1 10);
do
  php bin/console app:user-provisioning "add-user" "dark-user$i@example.com" "password" --name="Dark User $i" --darkm=true;
done