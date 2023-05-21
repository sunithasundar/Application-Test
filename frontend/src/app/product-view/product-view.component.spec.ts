import { ComponentFixture, TestBed, async, fakeAsync } from '@angular/core/testing';
import { ProductViewComponent } from './product-view.component';
import { HttpClientTestingModule, HttpTestingController } from '@angular/common/http/testing';
import { HttpClientModule, HttpClient } from '@angular/common/http';
import { ProductService } from 'src/app/service/product/product.service';
import { AlertService } from 'src/app/service/alert/alert.service';
import { RootService } from 'src/app/service/root/root.service';
import { NgxDatatableModule } from '@swimlane/ngx-datatable';
import { BsModalService, BsModalRef } from 'ngx-bootstrap/modal';

import {RouterTestingModule} from "@angular/router/testing";
import {of} from "rxjs";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";

describe('ProductViewComponent', () => {
  let component: ProductViewComponent;
  let fixture: ComponentFixture<ProductViewComponent>;
  let productService: ProductService;
  let alertService: AlertService;
  let rootService: RootService;
  let httpMock: HttpTestingController;

  let mockApiService : any;
  let productServiceSpy : jasmine.SpyObj<HttpClient>;
  let PRODUCTS = [
    {id:"1",name:"Liquid Saffron",state:"NY",zip:"08998",amount:"25.43",qty:"7",item:"XCD45300"},
    {id:"2",name:"Mostly Slugs",state:"PA",zip:"19008",amount:"13.30",qty:"2",item:"AAH6748"},
    {id:"3",name:"Jump Stain",state:"CA",zip:"99388",amount:"56.00",qty:"3",item:"MKII4400"},
    {id:"4",name:"Scheckled Sherlock",state:"WA",zip:"88990",amount:"987.56",qty:"1",item:"TR909"}
  ];

   beforeEach(async () => {

    productServiceSpy = jasmine.createSpyObj( ['onSelect','onDelete','onMultiDelete']);
   await TestBed.configureTestingModule({
     declarations: [ProductViewComponent],
     imports: [ HttpClientTestingModule,FormsModule,ReactiveFormsModule,
       RouterTestingModule, HttpClientModule, NgxDatatableModule ],
     providers: [ProductService,
      AlertService, RootService, BsModalService, BsModalRef,
      {provide: productService, useValue: productServiceSpy},
      {provide: AlertService, useClass: AlertService},
      {provide: RootService, useClass: RootService}
     ]
   })
     .compileComponents();
   
   fixture = TestBed.createComponent(ProductViewComponent);
   component = fixture.componentInstance;
   productService =  TestBed.inject(ProductService);
   productServiceSpy = TestBed.inject(HttpClient) as jasmine.SpyObj<HttpClient>;

   mockApiService = jasmine.createSpyObj(['selectData','deleteData','deleteMulData'])
   httpMock = TestBed.inject(HttpTestingController);
   fixture.detectChanges();

 });

  afterEach(() => {
    httpMock.verify();
  });

  it('should call readProduct', () => {
    const readRequest = httpMock.expectOne('http://localhost:8000/api/readProduct');
    expect(readRequest.request.method).toBe('GET');

    readRequest.flush(httpMock);
    expect(component.onSelect).toBeTruthy();
  })

  it('Should call post method to delete', () =>{
    let orderData = {id: "5"};
    spyOn(component,"onDelete").and.callFake(() => {
      return of(orderData);
    });
    component.onDelete(orderData);
    
    const httpRequest = httpMock.expectOne('http://localhost:8000/api/readProduct');
    expect(httpRequest.request.method).toBe('GET');

    httpRequest.flush(httpMock);
    expect(component.onDelete).toBeTruthy();
  })

  it('Should call post method to multiple delete', () =>{

    let orderDatas = {ids : ["6", "7"]};
    spyOn(component,"onMultiDelete").and.callFake(() => {
      return of(orderDatas);
    });
    component.onMultiDelete();

    const httpRequest = httpMock.expectOne('http://localhost:8000/api/readProduct');
    expect(httpRequest.request.method).toBe('GET');

    httpRequest.flush(httpMock);
    expect(component.onDelete).toBeTruthy();
  })

});