            <!-- Modal -->
            <div class="modal fade modal-xl" id="ModalTabAssociative" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tabelle Associative</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="container text-center">
                                <div class="row">
                                    <div class="col" id="tabelladescassociative">
                                        Column
                                    </div>
                                    <div class="col" id="tabellacatassociative">
                                        Column
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="closebtn" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->

            <!-- Jquery CDN -->
            <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
            <!-- End of Jquery CDN -->
            <script>

                $(document).ready(function() {
                const myModalEl = document.getElementById('ModalTabAssociative')
                myModalEl.addEventListener('shown.bs.modal', event => {
                    gettabassdescrizione()
                    gettabasscategoria()
                })

                    function gettabassdescrizione() {
                        $.ajax({
                            type: 'POST',
                            url: './php/preleva_dati_descassociative.php',
                            data: {
                                ajax: 1,

                            },
                            success: function(response) {
                                $("#tabelladescassociative").html(response);
                            }
                        });

                    }

                    function gettabasscategoria() {
                        $.ajax({
                            type: 'POST',
                            url: './php/preleva_dati_catassociative.php',
                            data: {
                                ajax: 1,

                            },
                            success: function(response) {
                                $("#tabellacatassociative").html(response);
                            }
                        });

                    }
                })
            </script>