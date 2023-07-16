import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { HttpClientModule } from '@angular/common/http';
import { RouterModule } from '@angular/router';
import { CommonModule } from '@angular/common';
import { FormsModule,ReactiveFormsModule  } from '@angular/forms';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { ProductViewComponent } from './product-view/product-view.component';
import { NgxDatatableModule, SelectionType } from '@swimlane/ngx-datatable';
import { ProductCreateComponent } from './product-create/product-create.component';
import { ModalModule } from 'ngx-bootstrap/modal';

@NgModule({
  declarations: [
    AppComponent,
    ProductViewComponent,
    ProductCreateComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    RouterModule,
    NgxDatatableModule,
    ReactiveFormsModule,
    FormsModule,
    ModalModule.forRoot()
  ],
  exports: [ ModalModule],
  providers: [],
  bootstrap: [AppComponent]
})

export class AppModule { }
