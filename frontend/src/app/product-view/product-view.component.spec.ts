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

    productServiceSpy = jasmine.createSpyObj( ['onRead','onDelete','onMultiDelete','showPopup']);
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
    const readRequest = httpMock.expectOne('http://localhost/Application-Test/backend/api/routes/product.php');
    expect(readRequest.request.method).toBe('GET');

    readRequest.flush(httpMock);
    expect(component.onRead).toBeTruthy();
  })

  /**
   * @desc calling onDelete method 
   */ 
  it('Should call method to delete', () =>{
    let orderData = 3;
    spyOn(component,"onDelete").and.callFake(() => {
      return of(orderData);
    });
    component.onDelete(orderData);
    
    const httpRequest = httpMock.expectOne('http://localhost/Application-Test/backend/api/routes/product.php');
    expect(httpRequest.request.method).toBe('GET');

    httpRequest.flush(httpMock);
    expect(component.onDelete).toBeTruthy();
  })

    /**
   * @desc calling onDelete method 
   */ 
  it('Should call method to delete invalid id', () =>{
    let orderData = 5;
    spyOn(component,"onDelete").and.callFake(() => {
      return of(orderData);
    });
    component.onDelete(orderData);
    
    const httpRequest = httpMock.expectOne('http://localhost/Application-Test/backend/api/routes/product.php');
    expect(httpRequest.request.method).toBe('GET');

    httpRequest.flush(httpMock);
    expect(component.onDelete).toBeTruthy();
  })

  it('Should call update method', () =>{  
    let orderData = {data: {id:"1",name:"Liquid Saffron",state:"NY",zip:"08998",amount:"25.43",qty:"7",item:"XCD45300"},id:2}; 
  
    spyOn(component,"showPopup").and.callFake(() => {
      return of(orderData,true);
    });
    component.showPopup(orderData);

    const httpRequest = httpMock.expectOne('http://localhost/Application-Test/backend/api/routes/product.php');
    expect(httpRequest.request.method).toBe('GET');

    httpRequest.flush(httpMock);    
    expect(component.showPopup(orderData)).toBeTruthy();
  })

  it('Should call update method Invalid Name', () =>{  
    let orderData = {data: {id:"1",name:"Liq",state:"NY",zip:"08998",amount:"25.43",qty:"7",item:"XCD45300"},id:2}; 
  
    spyOn(component,"showPopup").and.callFake(() => {
      return of(orderData,true);
    });
    component.showPopup(orderData);

    const httpRequest = httpMock.expectOne('http://localhost/Application-Test/backend/api/routes/product.php');
    expect(httpRequest.request.method).toBe('GET');

    httpRequest.flush(httpMock);    
    expect(component.showPopup(orderData.data.name)).toBeTruthy();
  })

  it('Should call update method Invalid Zip', () =>{  
    let orderData = {data: {id:"1",name:"Liquid",state:"NY",zip:"443",amount:"25.43",qty:"7",item:"XCD45300"},id:2}; 
  
    spyOn(component,"showPopup").and.callFake(() => {
      return of(orderData,true);
    });
    component.showPopup(orderData);

    const httpRequest = httpMock.expectOne('http://localhost/Application-Test/backend/api/routes/product.php');
    expect(httpRequest.request.method).toBe('GET');

    httpRequest.flush(httpMock);    
    expect(component.showPopup(orderData.data.zip)).toBeTruthy(); 
  })


  /**
   * @desc calling onMultiDelete method
   */ 
  it('Should call method to multiple delete', () =>{

    let orderDatas = {ids : ["2", "3"]};
    spyOn(component,"onMultiDelete").and.callFake(() => {
      return of(orderDatas);
    });
    component.onMultiDelete();

    const httpRequest = httpMock.expectOne('http://localhost/Application-Test/backend/api/routes/product.php');
    expect(httpRequest.request.method).toBe('GET');

    httpRequest.flush(httpMock);
    expect(component.onMultiDelete).toBeTruthy();
  })

});