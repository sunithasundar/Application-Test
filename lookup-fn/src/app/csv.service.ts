import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { RootService } from './root.service';

@Injectable({
  providedIn: 'root'
})
export class CsvService {

  constructor(private Root: RootService) { }

  uploadCsv(data: any) {
    return this.Root.post('api/upload-csv', data);
  }

  saveCsv(data: any) {
    return this.Root.post('api/save-csv', data);
  }
}
