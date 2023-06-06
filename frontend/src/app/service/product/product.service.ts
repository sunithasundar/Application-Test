import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { HttpClient } from '@angular/common/http';
import { AlertService } from 'src/app/service/alert/alert.service';
import { Product } from 'src/app/interface/product';
import { Observable } from  'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ProductService {

  baseURL = environment.baseURL;
  path = ''; url = '';
  
  constructor(
      private http: HttpClient,
      private Alert: AlertService
  ) { }

   /**
   * @desc create product 
   * @param data
   * @return response
   */
  createProduct(data: Object) : Observable<Product> { //api call to backend laravel to create product
    this.path ='api/createProduct';
    this.url = `${this.baseURL}${this.path}`;
    return this.http.post<Product>(this.url, data);
  }

  /**
   * @desc read product 
   * @param 
   * @return response
   */
  readProduct() : Observable<Product> { //api call to backend laravel to read product 
    this.path ='api/readProduct';
    this.url = `${this.baseURL}${this.path}`;
    return this.http.get<Product>(this.url);
  }

   /**
   * @desc udpate product 
   * @param data
   * @return response
   */
  updateProduct(data: Object) : Observable<Product> { //api call to backend laravel to update product 
    this.path ='api/updateProduct';
    this.url = `${this.baseURL}${this.path}`;
    return this.http.post<Product>(this.url, data);
  }

  /**
   * @desc delete product 
   * @param data
   * @return response
   */
  deleteProduct(data: Object) : Observable<Product> {  //api call to backend laravel to delete product 
    this.path ='api/deleteProduct';
    this.url = `${this.baseURL}${this.path}`;
    return this.http.post<Product>(this.url, data);
  }

  /**
   * @desc delete multiple product 
   * @param data
   * @return response
   */
  deleteMultipleProduct(data: Object) : Observable<Product> {  //api call to backend laravel to delete multiple products 
    this.path ='api/deleteMultipleProduct';
    this.url = `${this.baseURL}${this.path}`;
    return this.http.post<Product>(this.url, data);
  }

  /**
   * @desc handle error message on failure from backend
   * @param response
   * @return warning via toast
   */
  handleMessage(response:any) { 
    let message = '';
    if (response.error) {
      if (response.error.message) {
        message = response.error.message; //response message
      }      
      this.Alert.showToast("Warning",message,'red');
    }
  }
}
