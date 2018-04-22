import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

import { Shop, Location } from '../_models/index';

@Injectable()
export class ShopService {
    constructor(private http: HttpClient) { }

    getNearby(page: number, location: Location) {
        let url = '/api/shops/nearby?page=' + page;
        if(location && location.latitude && location.longitude)
            url += '&latitude=' + location.latitude + '&longitude=' + location.longitude;

        return this.http.get<any>(url);
    }

    like(id: number) {
        return this.http.post<any>('/api/shops/' + id + '/like');
    }
}
