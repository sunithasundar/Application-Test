import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { RootService } from 'src/app/service/root/root.service';

@Injectable({
  providedIn: 'root'
})
export class ProductService {

  constructor(private Root: RootService) { }

  createProduct(data: any) {
    return this.Root.post('api/createProduct', data);
  }

  readProduct() {
    return this.Root.get('api/readProduct');
  }

  updateProduct(data: any) {
    return this.Root.post('api/updateProduct', data);
  }

  deleteProduct(data: any) {
    return this.Root.post('api/deleteProduct', data);
  }
}
