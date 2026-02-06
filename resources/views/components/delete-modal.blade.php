<!-- Global Delete Modal -->
<div class="modal fade" id="globalDeleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">DELETE CONFIRMATION</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center">
                Are you sure want to delete ?
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">
                    Cancel
                </button>

                <form id="globalDeleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        Yes, Delete
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
