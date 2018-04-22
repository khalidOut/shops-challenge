import { Component, OnInit } from '@angular/core';

import { Router, ActivatedRoute } from '@angular/router';

import { Shop } from '../_models/index';
import { AlertService, ShopService } from '../_services/index';

@Component({
    moduleId: module.id.toString(),
    templateUrl: 'preferred.component.html'
})

export class PreferredComponent implements OnInit {
    shops: Shop[] = [];
    loading = false;
    page = 1;
    hasNextPage = false;

    constructor(
        private router: Router,
        private shopService: ShopService,
        private alertService: AlertService) { }

    ngOnInit() {
        this.loadPreferredShops();
    }

    private loadPreferredShops() {
        this.loading = true;
        this.shopService.getPreferred(this.page)
            .subscribe(res => {
                res['hydra:member'].forEach(member => {
                    let shop = new Shop();
                    shop.id = member.id;
                    shop.picture = member.picture;
                    shop.name = member.name;
                    shop.email = member.email;
                    shop.city = member.city;
                    shop.latitude = member.latitude;
                    shop.longitude = member.longitude;

                    this.shops.push(shop);
                });

                this.loading = false;

                //show "Load more" button if next page available
                this.hasNextPage = (res['hydra:view'] && res['hydra:view']['hydra:next']) ? true : false;
            },
            error => {
                this.router.navigate(['/login']);
            });
    }

    private loadMore() {
        this.page++;
        this.loadPreferredShops();
    }
}
