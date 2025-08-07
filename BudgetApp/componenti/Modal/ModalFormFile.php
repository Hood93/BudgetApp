<!-- Modal -->
<div class="modal fade" id="ModalFile" tabindex="-1" aria-labelledby="ModalFile" data-bs-backdrop="static" aria-hidden="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="ModalFile">Upload File Budget</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="php/uploadcsvbudget.php" method="post" enctype="multipart/form-data">
                    <div class="input-group mb-3">
                    <input type="file" class="form-control" id="formFile" name="formFile">
                    <input type="submit" name="submit" value="upload" class="btn btn-dark">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>