import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { RootService } from 'src/app/service/root/root.service';
import { Product } from 'src/app/interface/product';

@Injectable({
  providedIn: 'root'
})
export class ProductService {

  constructor(private Root: RootService) { }

  createProduct(data: Object) { //api call to backend laravel to create product
    return this.Root.post('api/createProduct', data);
  }

  readProduct() { //api call to backend laravel to read product 
    return this.Root.get('api/readProduct');
  }

  updateProduct(data: Object) { //api call to backend laravel to update product 
    return this.Root.post('api/updateProduct', data);
  }

  deleteProduct(data: Object) { //api call to backend laravel to delete product 
    return this.Root.post('api/deleteProduct', data);
  }

  deleteMultipleProduct(data: Object) { //api call to backend laravel to delete multiple products 
    return this.Root.post('api/deleteMultipleProduct', data);
  }
}
