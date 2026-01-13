<div class="modal" id="modal-delete-siswa">
  <div class="modal-dialog modal-md modal-dialog-scrollable">
    <form action="" id="form-delete-many">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title fw-bold ">Hapus Siswa</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">

            {{-- <div class="custom-control custom-checkbox">
              <input type="checkbox" name="siswa_id[]" class="custom-control-input custom-control-input-danger" value="{{ $data->id }}">
            </div> --}}
            <div class="table-responsive">
              <table id="myTableDelete" class="table table-sm table-hover mb-0 table-striped " style="width: 100%">
                <thead>
                  <tr class="bg-dark text-white header-table {{ Auth::user()->dark_mode == '1' ? 'bg-light' : '' }}">
                    <th scope="col">Tandai</th>
                    <th scope="col" style="min-width: 150px">Nama</th>
                    <th scope="col">Kelas</th>
                    <th scope="col">NIS/NISN</th>
                    <th scope="col">L/P</th>
                  </tr>
                </thead>
              </table>
            </div>

          </div>
          <div class="modal-footer justify-content-between">
            <div class="form-check">
              <input type="checkbox" class="form-check-input" id="store-confirm" required checked>
              <label class="form-check-label" for="store-confirm">Saya yakin sudah memilih dengan benar</label>
            </div>
            <button type="submit" class="btn btn-danger" id="destroy-many-button">Hapus</button>
          </div>
      </div>
    </form>
  </div>
</div>
