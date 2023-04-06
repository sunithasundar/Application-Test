import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { RootService } from './root.service';

@Injectable({
  providedIn: 'root'
})
export class CsvService {

  constructor(private Root: RootService) { }

  create(data: any) {
    return this.Root.post('api/create', data);
  }

  read(data: any) {
    return this.Root.post('api/read', data);
  }

  update(data: any) {
    return this.Root.post('api/update', data);
  }

  delete(data: any) {
    return this.Root.post('api/delete', data);
  }
}
