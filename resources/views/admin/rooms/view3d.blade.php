@extends('admin.layouts.app')
@section('title', '3D Hotel View & Room Builder')

@push('styles')
<style>
    #canvas-container {
        width: 100%;
        height: 620px;
        position: relative;
        background: radial-gradient(circle at center, #1e293b 0%, #0f172a 100%);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: inset 0 0 20px rgba(0,0,0,0.5);
    }
    #three-canvas {
        width: 100%;
        height: 100%;
        display: block;
    }
    .canvas-overlay-top {
        position: absolute;
        top: 16px;
        left: 16px;
        right: 16px;
        z-index: 10;
        pointer-events: none;
    }
    .canvas-overlay-top * {
        pointer-events: auto;
    }
    .canvas-controls-panel {
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        padding: 10px 16px;
        color: #fff;
    }
    .canvas-legend {
        position: absolute;
        bottom: 16px;
        left: 16px;
        z-index: 10;
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        padding: 8px 14px;
        color: #fff;
    }
    #room-tooltip {
        position: absolute;
        display: none;
        z-index: 100;
        background: rgba(15, 23, 42, 0.9);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(59, 130, 246, 0.5);
        color: #fff;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 0.825rem;
        pointer-events: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.4);
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="fw-bold mb-0"><i class="bi bi-box-seam text-primary me-2"></i>3D Hotel Floorplan & Visualizer</h4>
        <small class="text-muted">Interactive 3D building view & room layout manager</small>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addRoom3dModal">
            <i class="bi bi-plus-lg me-1"></i> Add Room
        </button>
        <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-grid me-1"></i> 2D Grid View
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-2">
        <div id="canvas-container">
            {{-- Top Controls Panel Overlay --}}
            <div class="canvas-overlay-top d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="canvas-controls-panel d-flex align-items-center gap-3">
                    {{-- Hotel Selector --}}
                    <form method="GET" action="{{ route('admin.rooms.view3d') }}" class="d-flex align-items-center gap-2">
                        <label class="small text-muted mb-0">Hotel:</label>
                        <select name="hotel_id" class="form-select form-select-sm bg-dark text-white border-secondary" style="width: 200px;" onchange="this.form.submit()">
                            @foreach($hotels as $h)
                                <option value="{{ $h->id }}" {{ (string)$selectedHotelId === (string)$h->id ? 'selected' : '' }}>{{ $h->name }}</option>
                            @endforeach
                        </select>
                    </form>

                    {{-- Floor Filter --}}
                    <div class="d-flex align-items-center gap-2">
                        <label class="small text-muted mb-0">Floor:</label>
                        <select id="floor3dFilter" class="form-select form-select-sm bg-dark text-white border-secondary" style="width: 140px;">
                            <option value="all">All Floors</option>
                            @foreach($floors as $f)
                                <option value="{{ $f->id }}">Floor {{ $f->floor_number ?? $f->number }} ({{ $f->name }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Camera Presets --}}
                <div class="canvas-controls-panel d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-xs btn-outline-light" id="camIsoBtn" title="3D Isometric View"><i class="bi bi-box me-1"></i> 3D</button>
                    <button type="button" class="btn btn-xs btn-outline-light" id="camTopBtn" title="Top Down 2D Floorplan"><i class="bi bi-square me-1"></i> Top 2D</button>
                    <button type="button" class="btn btn-xs btn-outline-light" id="camResetBtn" title="Reset View"><i class="bi bi-arrow-counterclockwise me-1"></i> Reset</button>
                </div>
            </div>

            {{-- Live 3D Tooltip --}}
            <div id="room-tooltip"></div>

            {{-- Canvas Legend --}}
            <div class="canvas-legend d-flex align-items-center gap-3 small">
                <span class="d-flex align-items-center gap-1"><span style="width:12px;height:12px;background:#10b981;display:inline-block;border-radius:2px;"></span> Available (<strong id="cnt-avail">0</strong>)</span>
                <span class="d-flex align-items-center gap-1"><span style="width:12px;height:12px;background:#3b82f6;display:inline-block;border-radius:2px;"></span> Occupied (<strong id="cnt-occ">0</strong>)</span>
                <span class="d-flex align-items-center gap-1"><span style="width:12px;height:12px;background:#f59e0b;display:inline-block;border-radius:2px;"></span> Maintenance (<strong id="cnt-maint">0</strong>)</span>
                <span class="d-flex align-items-center gap-1"><span style="width:12px;height:12px;background:#ef4444;display:inline-block;border-radius:2px;"></span> Out of Order (<strong id="cnt-ooo">0</strong>)</span>
            </div>

            {{-- 3D WebGL Canvas --}}
            <canvas id="three-canvas"></canvas>
        </div>
    </div>
</div>

{{-- Offcanvas Room Details Drawer --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="roomDetailDrawer" aria-labelledby="roomDetailDrawerLabel">
    <div class="offcanvas-header bg-dark text-white">
        <h5 class="offcanvas-title fw-bold" id="roomDetailDrawerLabel"><i class="bi bi-door-open me-2"></i>Room <span id="drawer-room-num"></span></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="mb-3 text-center py-3 bg-light rounded border">
            <span id="drawer-room-status-badge" class="badge fs-6 mb-2"></span>
            <h3 class="fw-bold mb-0 text-primary" id="drawer-room-type"></h3>
            <small class="text-muted" id="drawer-room-building-floor"></small>
        </div>

        <h6 class="fw-bold border-bottom pb-2 mb-3">Room Information</h6>
        <ul class="list-group list-group-flush mb-4 small">
            <li class="list-group-item d-flex justify-content-between px-0">
                <span class="text-muted">Hotel:</span>
                <strong id="drawer-room-hotel"></strong>
            </li>
            <li class="list-group-item d-flex justify-content-between px-0">
                <span class="text-muted">Base Price:</span>
                <strong id="drawer-room-price" class="text-success"></strong>
            </li>
            <li class="list-group-item d-flex justify-content-between px-0">
                <span class="text-muted">Housekeeping:</span>
                <strong id="drawer-room-housekeeping"></strong>
            </li>
            <li class="list-group-item d-flex justify-content-between px-0">
                <span class="text-muted">Active:</span>
                <span id="drawer-room-active" class="badge bg-success">Active</span>
            </li>
        </ul>

        <h6 class="fw-bold border-bottom pb-2 mb-3">Quick Status Actions</h6>
        <div class="d-grid gap-2 mb-4">
            <button type="button" class="btn btn-outline-success btn-sm w-100 status-btn" data-status="available"><i class="bi bi-check-circle me-1"></i> Mark Available</button>
            <button type="button" class="btn btn-outline-primary btn-sm w-100 status-btn" data-status="occupied"><i class="bi bi-person-check me-1"></i> Mark Occupied</button>
            <button type="button" class="btn btn-outline-warning btn-sm w-100 status-btn" data-status="maintenance"><i class="bi bi-tools me-1"></i> Mark Maintenance</button>
        </div>

        <div class="d-grid">
            <a id="drawer-edit-btn" href="#" class="btn btn-primary"><i class="bi bi-pencil me-1"></i> Edit Full Room Details</a>
        </div>
    </div>
</div>

{{-- Add Room Modal --}}
<div class="modal fade" id="addRoom3dModal" tabindex="-1" aria-labelledby="addRoom3dModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.rooms.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="addRoom3dModalLabel"><i class="bi bi-plus-square me-2"></i>Add New Room (3D Visualizer)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Hotel <span class="text-danger">*</span></label>
                        <select name="hotel_id" id="modal_hotel_id" class="form-select" required>
                            @foreach($hotels as $h)
                                <option value="{{ $h->id }}" {{ (string)$selectedHotelId === (string)$h->id ? 'selected' : '' }}>{{ $h->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Room Number <span class="text-danger">*</span></label>
                        <input type="text" name="room_number" class="form-control" placeholder="e.g. 101, 202, 305" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Room Type <span class="text-danger">*</span></label>
                        <select name="room_type_id" class="form-select" required>
                            <option value="">Select Room Type</option>
                            @foreach($roomTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }} ({{ number_format($type->base_price ?? $type->base_rate ?? 0, 2) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Floor</label>
                            <select name="floor_id" class="form-select">
                                <option value="">Select Floor</option>
                                @foreach($floors as $f)
                                    <option value="{{ $f->id }}">Floor {{ $f->floor_number ?? $f->number }} ({{ $f->name }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Building</label>
                            <select name="building_id" class="form-select">
                                <option value="">Select Building</option>
                                @foreach($buildings as $b)
                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="available">Available</option>
                            <option value="occupied">Occupied</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="out_of_order">Out of Order</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Save & Add to 3D View</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const rawRoomsData = @json($rooms);
    const canvas = document.getElementById('three-canvas');
    const container = document.getElementById('canvas-container');
    const tooltip = document.getElementById('room-tooltip');
    
    // Status counts
    let cntAvail = 0, cntOcc = 0, cntMaint = 0, cntOoo = 0;
    rawRoomsData.forEach(r => {
        if (r.status === 'available') cntAvail++;
        else if (r.status === 'occupied') cntOcc++;
        else if (r.status === 'maintenance') cntMaint++;
        else cntOoo++;
    });
    document.getElementById('cnt-avail').innerText = cntAvail;
    document.getElementById('cnt-occ').innerText = cntOcc;
    document.getElementById('cnt-maint').innerText = cntMaint;
    document.getElementById('cnt-ooo').innerText = cntOoo;

    // Scene, Camera, Renderer
    const scene = new THREE.Scene();
    scene.background = new THREE.Color(0x0f172a);
    scene.fog = new THREE.FogExp2(0x0f172a, 0.015);

    const camera = new THREE.PerspectiveCamera(45, container.clientWidth / container.clientHeight, 0.1, 1000);
    camera.position.set(30, 25, 35);

    const renderer = new THREE.WebGLRenderer({ canvas: canvas, antialias: true, alpha: true });
    renderer.setSize(container.clientWidth, container.clientHeight);
    renderer.setPixelRatio(window.devicePixelRatio);
    renderer.shadowMap.enabled = true;
    renderer.shadowMap.type = THREE.PCFSoftShadowMap;

    // Orbit Controls
    const controls = new THREE.OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.05;
    controls.maxPolarAngle = Math.PI / 2 - 0.05; // don't go below ground

    // Lighting
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.7);
    scene.add(ambientLight);

    const dirLight = new THREE.DirectionalLight(0xffffff, 0.8);
    dirLight.position.set(40, 60, 20);
    dirLight.castShadow = true;
    dirLight.shadow.mapSize.width = 1024;
    dirLight.shadow.mapSize.height = 1024;
    scene.add(dirLight);

    const pointLight = new THREE.PointLight(0x3b82f6, 1.2, 100);
    pointLight.position.set(0, 30, 0);
    scene.add(pointLight);

    // Color Map by Status
    const statusColors = {
        'available': 0x10b981,   // Green
        'occupied': 0x3b82f6,    // Blue
        'maintenance': 0xf59e0b, // Orange
        'out_of_order': 0xef4444 // Red
    };

    // Ground Plane
    const groundGeo = new THREE.PlaneGeometry(80, 80);
    const groundMat = new THREE.MeshStandardMaterial({ color: 0x1e293b, roughness: 0.8, metalness: 0.2 });
    const ground = new THREE.Mesh(groundGeo, groundMat);
    ground.rotation.x = -Math.PI / 2;
    ground.position.y = -0.1;
    ground.receiveShadow = true;
    scene.add(ground);

    // Grid Helper
    const gridHelper = new THREE.GridHelper(80, 40, 0x3b82f6, 0x334155);
    gridHelper.position.y = 0.01;
    scene.add(gridHelper);

    // Group for Rooms & Floors
    const buildingGroup = new THREE.Group();
    scene.add(buildingGroup);

    const roomMeshes = [];
    const floorSlabs = [];

    // Group Rooms by Floor
    const floorMap = {};
    rawRoomsData.forEach(room => {
        const floorId = room.floor_id || 1;
        if (!floorMap[floorId]) floorMap[floorId] = [];
        floorMap[floorId].push(room);
    });

    const floorIds = Object.keys(floorMap);
    const roomsPerRow = 4;
    const boxWidth = 4;
    const boxHeight = 2.5;
    const boxDepth = 4;
    const gap = 1.2;

    floorIds.forEach((floorId, fIndex) => {
        const floorRooms = floorMap[floorId];
        const yPos = fIndex * (boxHeight + 1.2) + boxHeight / 2 + 0.2;

        // Render Floor Slab
        const totalRows = Math.ceil(floorRooms.length / roomsPerRow);
        const slabW = roomsPerRow * (boxWidth + gap) + 2;
        const slabD = totalRows * (boxDepth + gap) + 2;

        const slabGeo = new THREE.BoxGeometry(slabW, 0.2, slabD);
        const slabMat = new THREE.MeshStandardMaterial({
            color: 0x334155,
            transparent: true,
            opacity: 0.6,
            roughness: 0.5
        });
        const slab = new THREE.Mesh(slabGeo, slabMat);
        slab.position.set(0, yPos - boxHeight/2 - 0.1, 0);
        slab.userData = { floorId: floorId };
        buildingGroup.add(slab);
        floorSlabs.push(slab);

        // Render Room Boxes
        floorRooms.forEach((room, rIndex) => {
            const row = Math.floor(rIndex / roomsPerRow);
            const col = rIndex % roomsPerRow;

            const xPos = (col - (roomsPerRow - 1) / 2) * (boxWidth + gap);
            const zPos = (row - (totalRows - 1) / 2) * (boxDepth + gap);

            const colorHex = statusColors[room.status] || 0x64748b;

            const roomGeo = new THREE.BoxGeometry(boxWidth, boxHeight, boxDepth);
            const roomMat = new THREE.MeshStandardMaterial({
                color: colorHex,
                roughness: 0.3,
                metalness: 0.1,
            });

            const mesh = new THREE.Mesh(roomGeo, roomMat);
            mesh.position.set(xPos, yPos, zPos);
            mesh.castShadow = true;
            mesh.receiveShadow = true;

            // Wireframe Outline for visual flair
            const edges = new THREE.EdgesGeometry(roomGeo);
            const lineMat = new THREE.LineBasicMaterial({ color: 0xffffff, transparent: true, opacity: 0.4 });
            const wireframe = new THREE.LineSegments(edges, lineMat);
            mesh.add(wireframe);

            mesh.userData = { room: room, defaultColor: colorHex, floorId: floorId };
            buildingGroup.add(mesh);
            roomMeshes.push(mesh);
        });
    });

    // Center camera on building
    controls.target.set(0, (floorIds.length * 3.7) / 2, 0);
    controls.update();

    // Raycasting for Interaction (Hover & Click)
    const raycaster = new THREE.Raycaster();
    const mouse = new THREE.Vector2();
    let hoveredMesh = null;

    function onPointerMove(event) {
        const rect = renderer.domElement.getBoundingClientRect();
        mouse.x = ((event.clientX - rect.left) / rect.width) * 2 - 1;
        mouse.y = -((event.clientY - rect.top) / rect.height) * 2 + 1;

        raycaster.setFromCamera(mouse, camera);
        const intersects = raycaster.intersectObjects(roomMeshes);

        if (intersects.length > 0) {
            const hit = intersects[0].object;
            if (hoveredMesh !== hit) {
                if (hoveredMesh) hoveredMesh.material.emissive.setHex(0x000000);
                hoveredMesh = hit;
                hoveredMesh.material.emissive.setHex(0x38bdf8);
            }

            const rData = hit.userData.room;
            tooltip.style.display = 'block';
            tooltip.style.left = (event.clientX - rect.left + 15) + 'px';
            tooltip.style.top = (event.clientY - rect.top + 15) + 'px';
            tooltip.innerHTML = `
                <div class="fw-bold"><i class="bi bi-door-open me-1"></i> Room ${rData.room_number || rData.number}</div>
                <div class="text-info">${rData.room_type?.name || 'Standard'}</div>
                <div>Status: <span class="badge bg-secondary">${rData.status}</span></div>
            `;
            container.style.cursor = 'pointer';
        } else {
            if (hoveredMesh) {
                hoveredMesh.material.emissive.setHex(0x000000);
                hoveredMesh = null;
            }
            tooltip.style.display = 'none';
            container.style.cursor = 'default';
        }
    }

    let activeRoom = null;
    let activeMesh = null;

    function onPointerClick(event) {
        raycaster.setFromCamera(mouse, camera);
        const intersects = raycaster.intersectObjects(roomMeshes);

        if (intersects.length > 0) {
            activeMesh = intersects[0].object;
            activeRoom = activeMesh.userData.room;
            openRoomDrawer(activeRoom);
        }
    }

    container.addEventListener('pointermove', onPointerMove);
    container.addEventListener('click', onPointerClick);

    function updateLegendCounts() {
        let cntAvail = 0, cntOcc = 0, cntMaint = 0, cntOoo = 0;
        roomMeshes.forEach(m => {
            const s = m.userData.room.status;
            if (s === 'available') cntAvail++;
            else if (s === 'occupied') cntOcc++;
            else if (s === 'maintenance') cntMaint++;
            else cntOoo++;
        });
        document.getElementById('cnt-avail').innerText = cntAvail;
        document.getElementById('cnt-occ').innerText = cntOcc;
        document.getElementById('cnt-maint').innerText = cntMaint;
        document.getElementById('cnt-ooo').innerText = cntOoo;
    }

    // Open Offcanvas Drawer
    function openRoomDrawer(room) {
        document.getElementById('drawer-room-num').innerText = room.room_number || room.number;
        document.getElementById('drawer-room-type').innerText = room.room_type?.name || 'Standard Room';
        document.getElementById('drawer-room-building-floor').innerText = `Floor ${room.floor?.floor_number || room.floor_id || 1} • ${room.building?.name || 'Main Building'}`;
        document.getElementById('drawer-room-hotel').innerText = room.hotel?.name || 'Active Hotel';
        document.getElementById('drawer-room-price').innerText = '$' + (room.room_type?.base_price || room.room_type?.base_rate || '120.00');
        document.getElementById('drawer-room-housekeeping').innerText = (room.housekeeping_status || 'Clean').toUpperCase();

        const badge = document.getElementById('drawer-room-status-badge');
        badge.innerText = (room.status || 'AVAILABLE').toUpperCase();
        badge.className = 'badge fs-6 mb-2 bg-' + (room.status === 'available' ? 'success' : (room.status === 'occupied' ? 'primary' : 'warning'));

        document.getElementById('drawer-edit-btn').href = `/admin/rooms/${room.id}/edit`;

        const bsDrawer = new bootstrap.Offcanvas(document.getElementById('roomDetailDrawer'));
        bsDrawer.show();
    }

    // AJAX Quick Status Actions (No Page Refresh!)
    document.querySelectorAll('.status-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            if (!activeRoom) return;
            const newStatus = this.getAttribute('data-status');
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch(`/admin/rooms/${activeRoom.id}/status/${newStatus}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token || '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    activeRoom.status = newStatus;

                    // Update 3D mesh color in real time
                    if (activeMesh) {
                        const newHex = statusColors[newStatus] || 0x64748b;
                        activeMesh.material.color.setHex(newHex);
                        activeMesh.userData.defaultColor = newHex;
                    }

                    // Update drawer badge
                    const badge = document.getElementById('drawer-room-status-badge');
                    badge.innerText = newStatus.toUpperCase();
                    badge.className = 'badge fs-6 mb-2 bg-' + (newStatus === 'available' ? 'success' : (newStatus === 'occupied' ? 'primary' : 'warning'));

                    // Recalculate Live Legend Counts
                    updateLegendCounts();
                }
            })
            .catch(err => console.error('Status update error:', err));
        });
    });

    // Floor Filter
    document.getElementById('floor3dFilter').addEventListener('change', function (e) {
        const val = e.target.value;
        roomMeshes.forEach(mesh => {
            if (val === 'all' || (mesh.userData.floorId == val)) {
                mesh.visible = true;
            } else {
                mesh.visible = false;
            }
        });
        floorSlabs.forEach(slab => {
            if (val === 'all' || (slab.userData.floorId == val)) {
                slab.visible = true;
            } else {
                slab.visible = false;
            }
        });
    });

    // Camera Presets
    document.getElementById('camIsoBtn').addEventListener('click', () => {
        camera.position.set(30, 25, 35);
        controls.target.set(0, (floorIds.length * 3.7) / 2, 0);
    });

    document.getElementById('camTopBtn').addEventListener('click', () => {
        camera.position.set(0, 50, 0.1);
        controls.target.set(0, 0, 0);
    });

    document.getElementById('camResetBtn').addEventListener('click', () => {
        camera.position.set(30, 25, 35);
        controls.target.set(0, (floorIds.length * 3.7) / 2, 0);
    });

    // Window Resize
    window.addEventListener('resize', function () {
        camera.aspect = container.clientWidth / container.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(container.clientWidth, container.clientHeight);
    });

    // Animation Loop
    function animate() {
        requestAnimationFrame(animate);
        controls.update();
        renderer.render(scene, camera);
    }
    animate();

    // Modal Real-Time AJAX Options Loader
    const modalHotelSel = document.getElementById('modal_hotel_id');
    function loadModalOptions(hotelId) {
        if (!hotelId) return;
        fetch(`/admin/hotels/${hotelId}/options`)
            .then(res => res.json())
            .then(data => {
                const floorSel = document.querySelector('#addRoom3dModal select[name="floor_id"]');
                const bldgSel = document.querySelector('#addRoom3dModal select[name="building_id"]');
                const typeSel = document.querySelector('#addRoom3dModal select[name="room_type_id"]');

                if (floorSel) {
                    floorSel.innerHTML = '<option value="">Select Floor</option>';
                    data.floors.forEach(f => {
                        floorSel.innerHTML += `<option value="${f.id}">Floor ${f.number} (${f.name})</option>`;
                    });
                }
                if (bldgSel) {
                    bldgSel.innerHTML = '<option value="">Select Building</option>';
                    data.buildings.forEach(b => {
                        bldgSel.innerHTML += `<option value="${b.id}">${b.name}</option>`;
                    });
                }
                if (typeSel) {
                    typeSel.innerHTML = '<option value="">Select Room Type</option>';
                    data.room_types.forEach(t => {
                        typeSel.innerHTML += `<option value="${t.id}">${t.name} ($${t.price})</option>`;
                    });
                }
            });
    }
    if (modalHotelSel) {
        modalHotelSel.addEventListener('change', function() {
            loadModalOptions(this.value);
        });
    }
    document.getElementById('addRoom3dModal')?.addEventListener('show.bs.modal', function() {
        if (modalHotelSel && modalHotelSel.value) {
            loadModalOptions(modalHotelSel.value);
        }
    });
});
</script>
@endpush
