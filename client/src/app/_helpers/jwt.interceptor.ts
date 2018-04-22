import { Injectable } from '@angular/core';
import { HttpRequest, HttpHandler, HttpEvent, HttpInterceptor } from '@angular/common/http';
import { Observable } from 'rxjs/Observable';

@Injectable()
export class JwtInterceptor implements HttpInterceptor {
    intercept(request: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
        const base_url = 'http://shops.test';
        // add base_url and authorization header with jwt token if available
        let currentUser = JSON.parse(localStorage.getItem('currentUser'));
        if (currentUser && currentUser.token) {
            request = request.clone({
                url: base_url + request.url,
                setHeaders: {
                    Authorization: `Bearer ${currentUser.token}`
                }
            });
        }
        else {
            request = request.clone({
                url: base_url + request.url
            });
        }

        return next.handle(request);
    }
}
