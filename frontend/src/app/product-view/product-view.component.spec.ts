import { ComponentFixture, TestBed, async, fakeAsync } from '@angular/core/testing';
import { ProductViewComponent } from './product-view.component';
import { HttpClientTestingModule, HttpTestingController } from '@angular/common/http/testing';
import { HttpClientModule, HttpClient } from '@angular/common/http';
import { ProductService } from 'src/app/service/product/product.service';
import { AlertService } from 'src/app/service/alert/alert.service';
import { NgxDatatableModule } from '@swimlane/ngx-datatable';
import { BsModalService, BsModalRef } from 'ngx-bootstrap/modal';

import {RouterTestingModule} from "@angular/router/testing";
import {of} from "rxjs";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";

describe('ProductViewComponent', () => {
  let component: ProductViewComponent;
  let fixture: ComponentFixture<ProductViewComponent>;
  let productService: ProductService;
  let httpMock: HttpTestingController;

  let productServiceSpy : jasmine.SpyObj<HttpClient>;

   beforeEach(async () => {

    productServiceSpy = jasmine.createSpyObj( ['onSelect','onDelete','onMultiDelete']);
   await TestBed.configureTestingModule({
     declarations: [ProductViewComponent],
     imports: [ HttpClientTestingModule,FormsModule,ReactiveFormsModule,
       RouterTestingModule, HttpClientModule, NgxDatatableModule ],
     providers: [ProductService,
      AlertService, BsModalService, BsModalRef,
      {provide: productService, useValue: productServiceSpy},
      {provide: AlertService, useClass: AlertService}
     ]
   })
     .compileComponents();
   
   fixture = TestBed.createComponent(ProductViewComponent);
   component = fixture.componentInstance;
   productService =  TestBed.inject(ProductService);
   productServiceSpy = TestBed.inject(HttpClient) as jasmine.SpyObj<HttpClient>;

   httpMock = TestBed.inject(HttpTestingController);
   fixture.detectChanges();

 });

  afterEach(() => {
    httpMock.verify();
  });

   /**
   * @desc readProduct to get list of products
   */ 
  it('should call readProduct', () => {
    const readRequest = httpMock.expectOne('http://localhost:8000/api/readProduct');
    expect(readRequest.request.method).toBe('GET');

    readRequest.flush(httpMock);
    expect(component.onSelect).toBeTruthy();
  })

  /**
   * @desc calling onDelete method 
   */ 
  it('Should call post method to delete', () =>{
    let orderData = 3;
    spyOn(component,"onDelete").and.callFake(() => {
      return of(orderData);
    });
    component.onDelete(orderData);
    
    const httpRequest = httpMock.expectOne('http://localhost:8000/api/readProduct');
    expect(httpRequest.request.method).toBe('GET');

    httpRequest.flush(httpMock);
    expect(component.onDelete).toBeTruthy();
  })

  /**
   * @desc calling onMultiDelete method
   */ 
  it('Should call post method to multiple delete', () =>{

    let orderDatas = {ids : ["6", "7"]};
    spyOn(component,"onMultiDelete").and.callFake(() => {
      return of(orderDatas);
    });
    component.onMultiDelete();

    const httpRequest = httpMock.expectOne('http://localhost:8000/api/readProduct');
    expect(httpRequest.request.method).toBe('GET');

    httpRequest.flush(httpMock);
    expect(component.onMultiDelete).toBeTruthy();
  })

});