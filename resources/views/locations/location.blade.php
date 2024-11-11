<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Locations</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1>Manage Locations</h1>

        <!-- Create Location Form -->
        <form id="createLocationForm">
            <div class="mb-3">
                <label for="name" class="form-label">Location Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="areaCode" class="form-label">Area Code</label>
                <input type="text" class="form-control" id="areaCode" name="areaCode" required>
            </div>
            <button type="submit" class="btn btn-primary">Create Location</button>
            <button type="button" class="btn btn-secondary" onclick="cancelCreate()">Cancel</button>
        </form>

        <hr>

        <div id="addLocation">
            <button type="button" class="btn btn-primary" onclick="showCreateLocationForm()">Add Location</button>
        </div>

        <!-- Edit Location Form (Hidden initially) -->
        <div id="editLocationContainer" style="display:none;">
            <h2>Edit Location</h2>
            <form id="editLocationForm">
                <div class="mb-3">
                    <label for="editName" class="form-label">Location Name</label>
                    <input type="text" class="form-control" id="editName" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="editAreaCode" class="form-label">Area Code</label>
                    <input type="text" class="form-control" id="editAreaCode" name="areaCode" required>
                </div>
                <button type="submit" class="btn btn-success">Update Location</button>
                <button type="button" class="btn btn-secondary" onclick="cancelEdit()">Cancel</button>
            </form>
        </div>

        <hr>

        <!-- Locations List -->
        <h2>All Locations</h2>
        <ul id="locationsList" class="list-group">
            <!-- Locations will be loaded here by JavaScript -->
        </ul>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            let editLocationId = null;
            $('#createLocationForm').hide();

            // Function to load all locations
            function loadLocations() {
                $.ajax({
                    url: "{{ route('getLocations') }}",
                    method: "GET",
                    success: function(data) {
                        $('#locationsList').empty();
                        if (data.length > 0) {
                            $.each(data, function(index, location) {
                                $('#locationsList').append(`
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    ${location.name} (Area Code: ${location.areaCode})
                                    <div>
                                        <button class="btn btn-warning btn-sm" onclick="editLocation(${location.id}, '${location.name}', '${location.areaCode}')">Edit</button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteLocation(${location.id})">Delete</button>
                                    </div>
                                </li>
                            `);
                            });
                        } else {
                            $('#locationsList').append('<li class="list-group-item">No locations found</li>');
                        }
                    },
                    error: function() {
                        alert('Failed to load locations');
                    }
                });
            }

            window.showCreateLocationForm = function() {
                $('#addLocation').hide();
                $('#createLocationForm').show();
            }

            // Load locations on page load
            loadLocations();

            // Create Location
            $('#createLocationForm').on('submit', function(e) {
                e.preventDefault();
                const name = $('#name').val();
                const areaCode = $('#areaCode').val();
                $.ajax({
                    url: "{{ route('createLocation') }}",
                    method: "POST",
                    data: {
                        name: name,
                        areaCode: areaCode,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function() {
                        loadLocations();
                        $('#createLocationForm')[0].reset();
                        // $('#createLocationForm').hide();
                    },
                    error: function() {
                        alert('Failed to create location');
                    }
                });
            });

             // Cancel Create
             window.cancelCreate = function() {
                $('#addLocation').show();
                $('#createLocationForm').hide();
                $('#createLocationForm')[0].reset();
            };

            // Edit Location
            window.editLocation = function(id, name, areaCode) {
                editLocationId = id;
                $('#editName').val(name);
                $('#editAreaCode').val(areaCode);
                $('#createLocationForm').hide();
                $('#addLocation').hide();
                $('#editLocationContainer').show();
            };

            // Cancel Edit
            window.cancelEdit = function() {
                editLocationId = null;
                $('#addLocation').show();
                $('#editLocationContainer').hide();
                $('#editLocationForm')[0].reset();
            };

            // Update Location
            $('#editLocationForm').on('submit', function(e) {
                e.preventDefault();
                const name = $('#editName').val();
                const areaCode = $('#editAreaCode').val();
                $.ajax({
                    url: `{{ url('location') }}/${editLocationId}`,
                    method: "PUT",
                    data: {
                        name: name,
                        areaCode: areaCode,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function() {
                        loadLocations();
                        $('#editLocationContainer').hide();
                        $('#addLocation').show();
                        $('#editLocationForm')[0].reset();
                    },
                    error: function() {
                        alert('Failed to update location');
                    }
                });
            });

            // Delete Location
            window.deleteLocation = function(id) {
                if (confirm('Are you sure you want to delete this location?')) {
                    $.ajax({
                        url: `{{ url('location') }}/${id}`,
                        method: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function() {
                            loadLocations();
                        },
                        error: function() {
                            alert('Failed to delete location');
                        }
                    });
                }
            };
        });
    </script>
</body>

</html>