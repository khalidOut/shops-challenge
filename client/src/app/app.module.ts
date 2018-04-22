import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule }    from '@angular/forms';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';

import { AppComponent } from './app.component';
import { routing } from './app.routing';

import { AlertComponent } from './_directives/index';
import { AuthGuard } from './_guards/index';
import { JwtInterceptor } from './_helpers/index';
import { AlertService, AuthenticationService, UserService, ShopService } from './_services/index';
import { HomeComponent } from './home/index';
import { LoginComponent } from './login/index';
import { RegisterComponent } from './register/index';

@NgModule({
  declarations: [
      AppComponent,
      AlertComponent,
      HomeComponent,
      LoginComponent,
      RegisterComponent
  ],
  imports: [
      BrowserModule,
      FormsModule,
      HttpClientModule,
      routing
  ],
  providers: [
      AuthGuard,
      AlertService,
      AuthenticationService,
      UserService,
      ShopService,
      {
        provide: HTTP_INTERCEPTORS,
        useClass: JwtInterceptor,
        multi: true
      },
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
