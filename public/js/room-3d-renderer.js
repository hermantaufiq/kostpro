import * as THREE from 'https://unpkg.com/three@0.160.0/build/three.module.js';

export function initRoom3DViewer(container, roomData) {
  if (!container) return null;

  // Clear previous content
  container.innerHTML = '';

  const width = container.clientWidth;
  const height = container.clientHeight || 400;

  // 1. SCENE & RENDERER SETUP
  const scene = new THREE.Scene();
  scene.background = new THREE.Color(0x1a1f2e);

  const camera = new THREE.PerspectiveCamera(55, width / height, 0.1, 100);

  const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: false });
  renderer.setSize(width, height);
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
  renderer.shadowMap.enabled = true;
  renderer.shadowMap.type = THREE.PCFSoftShadowMap;
  renderer.toneMapping = THREE.ACESFilmicToneMapping;
  renderer.toneMappingExposure = 1.1;
  container.appendChild(renderer.domElement);

  // 2. PARSE ROOM DATA
  // Default dimensions
  let W = 4; // Width (X-axis)
  let D = 4; // Depth (Z-axis)
  const H = 2.8; // Height (Y-axis)

  if (roomData.luas) {
    const match = String(roomData.luas).match(/([\d.,]+)\s*[x×]\s*([\d.,]+)/i);
    if (match) {
      W = parseFloat(match[1].replace(',', '.'));
      D = parseFloat(match[2].replace(',', '.'));
    } else {
      const num = parseFloat(String(roomData.luas).replace(/[^\d.,]/g, '').replace(',', '.'));
      if (!isNaN(num) && num > 0) {
        // Assume 4:3 ratio if only area is given
        W = Math.sqrt(num * 1.33);
        D = num / W;
      }
    }
  }

  // Ensure reasonable boundaries
  W = Math.max(2.5, Math.min(6, W));
  D = Math.max(2.5, Math.min(6, D));

  const facilities = (roomData.facilities || []).map(f => f.toLowerCase());
  
  // Determine if specific items exist
  const hasAC = facilities.some(f => f.includes('ac'));
  const hasWardrobe = facilities.some(f => f.includes('lemari') || f.includes('wardrobe'));
  const hasDesk = facilities.some(f => f.includes('meja') || f.includes('desk') || f.includes('belajar'));
  const hasEnsuiteBathroom = facilities.some(f => f.includes('kamar mandi dalam') || f.includes('bathroom') || f.includes('toilet'));
  const hasTV = facilities.some(f => f.includes('tv') || f.includes('televisi'));
  const hasWifi = facilities.some(f => f.includes('wifi') || f.includes('internet'));
  const hasFan = facilities.some(f => f.includes('kipas'));
  const hasFridge = facilities.some(f => f.includes('kulkas') || f.includes('fridge'));
  const hasSofa = facilities.some(f => f.includes('sofa') || f.includes('kursi santai'));
  const hasWindowOutside = facilities.some(f => f.includes('jendela luar') || f.includes('balkon'));

  // 3. LIGHTING SYSTEM
  const ambient = new THREE.AmbientLight(0xfff5e0, 0.6);
  scene.add(ambient);

  const sunLight = new THREE.DirectionalLight(0xfff8e7, 1.4);
  sunLight.position.set(W / 2 + 1, 5, D / 2 + 1);
  sunLight.castShadow = true;
  sunLight.shadow.mapSize.set(2048, 2048);
  sunLight.shadow.camera.near = 0.5;
  sunLight.shadow.camera.far = 20;
  sunLight.shadow.camera.left = -W;
  sunLight.shadow.camera.right = W;
  sunLight.shadow.camera.top = H * 1.5;
  sunLight.shadow.camera.bottom = -H * 1.5;
  scene.add(sunLight);

  const ceilingLight = new THREE.PointLight(0xfff0cc, 1.2, 8);
  ceilingLight.position.set(0, H - 0.3, 0);
  scene.add(ceilingLight);

  const windowGlow = new THREE.PointLight(0x87ceeb, 0.8, 5);
  windowGlow.position.set(-W / 2 + 0.2, H / 2 + 0.3, -D / 4);
  scene.add(windowGlow);

  // 4. MATERIALS DICTIONARY (Modern & Clean)
  const M = (color, opts = {}) => new THREE.MeshStandardMaterial({ color, roughness: 0.6, metalness: 0.15, ...opts });
  
  // Custom Colors from Database (or defaults)
  const wallColorStr = roomData.warna_dinding || '#f8fafc'; // modern off-white
  const floorColorStr = roomData.warna_lantai || '#d4d4d8'; // modern light grey/wood
  const bedColorStr = roomData.warna_kasur || '#ffffff';
  
  const wallColor = new THREE.Color(wallColorStr);
  const floorColor = new THREE.Color(floorColorStr);
  const bedColor = new THREE.Color(bedColorStr);

  const wallMat       = M(wallColor, { roughness: 0.85, metalness: 0.05 });
  const floorMat      = M(floorColor, { roughness: 0.4, metalness: 0.1 });
  const ceilMat       = M(0xffffff, { roughness: 0.9 });
  const bedFrameMat   = M(0x27272a, { roughness: 0.5 }); // Dark charcoal modern frame
  const mattressMat   = M(0xf1f5f9, { roughness: 0.8 });
  const pillowMat     = M(bedColor, { roughness: 0.9 });
  const blanketMat    = M(bedColor, { roughness: 0.8 });
  const deskMat       = M(0xe2e8f0, { roughness: 0.5 }); // Light modern desk
  const legMat        = M(0x18181b, { roughness: 0.3, metalness: 0.8 }); // Black metal legs
  const windowMat     = M(0xbae6fd, { transparent: true, opacity: 0.3, roughness: 0.05, metalness: 0.95 });
  const frameMat      = M(0x0f172a, { roughness: 0.4 }); // Dark window frames
  const doorMat       = M(0xf8fafc, { roughness: 0.6 }); // Clean white door
  const wardrobeMat   = M(0xf1f5f9, { roughness: 0.6 }); // White minimalist wardrobe
  const woodAccentMat = M(0x8b5a2b, { roughness: 0.6 }); // Wood accents
  const goldMat       = M(0x18181b, { metalness: 0.8, roughness: 0.2 }); // Black matte handles instead of gold
  const rugMat        = M(0x94a3b8, { roughness: 0.95 }); // Soft grey rug
  const rugPatMat     = M(0xcbd5e1, { roughness: 0.95 }); 
  const chairMat      = M(0x334155, { roughness: 0.8 }); // Slate modern chair
  const plasticMat    = M(0x1e293b, { roughness: 0.4 });
  const laptopMat     = M(0x94a3b8, { metalness: 0.8, roughness: 0.2 });
  const screenMat     = M(0x020617, { emissive: 0x0f172a, emissiveIntensity: 0.5 });
  const baseboardMat  = M(0xffffff, { roughness: 0.8 });
  const lightFixtureMat = M(0x0f172a, { emissive: 0xfff5c0, emissiveIntensity: 0.8 }); // Black modern fixture

  const clickableObjects = [];

  const add = (geo, mat, x, y, z, rx = 0, ry = 0, rz = 0, shadow = true, name = null, desc = null) => {
    const mesh = new THREE.Mesh(geo, mat);
    mesh.position.set(x, y, z);
    mesh.rotation.set(rx, ry, rz);
    if (shadow) {
      mesh.castShadow = true;
      mesh.receiveShadow = true;
    }
    scene.add(mesh);

    if (name) {
      mesh.userData = { name, desc };
      clickableObjects.push(mesh);
    }
    return mesh;
  };

  // Helper to add group
  const createGroup = (x, y, z, name = null, desc = null) => {
    const group = new THREE.Group();
    group.position.set(x, y, z);
    scene.add(group);
    if (name) {
      group.userData = { name, desc };
    }
    return group;
  };

  // 5. ROOM SHELL & STRUCTURAL ELEMENTS
  // Floor
  add(new THREE.BoxGeometry(W, 0.12, D), floorMat, 0, -0.06, 0, 0, 0, 0, true, "Lantai", `Lantai kamar dengan luas ${roomData.luas || (W.toFixed(1) + 'x' + D.toFixed(1))} m²`);
  
  // Ceiling
  add(new THREE.BoxGeometry(W, 0.08, D), ceilMat, 0, H + 0.04, 0, 0, 0, 0, false);

  // Walls (Leaving the front open for camera viewing)
  // Back Wall
  add(new THREE.BoxGeometry(W, H, 0.1), wallMat, 0, H / 2, -D / 2);
  // Left Wall
  add(new THREE.BoxGeometry(0.1, H, D), wallMat, -W / 2, H / 2, 0);
  // Right Wall
  add(new THREE.BoxGeometry(0.1, H, D), wallMat, W / 2, H / 2, 0);

  // Baseboard trims (Visual polish)
  add(new THREE.BoxGeometry(W - 0.1, 0.1, 0.04), baseboardMat, 0, 0.05, -D / 2 + 0.05, 0, 0, 0, false);
  add(new THREE.BoxGeometry(0.04, 0.1, D - 0.1), baseboardMat, -W / 2 + 0.05, 0.05, 0, 0, 0, 0, false);
  add(new THREE.BoxGeometry(0.04, 0.1, D - 0.1), baseboardMat, W / 2 - 0.05, 0.05, 0, 0, 0, 0, false);

  // 6. WINDOW (Left Wall)
  const windowZ = -D / 6;
  const windowW = hasWindowOutside ? 2.0 : 1.4;
  const windowH = hasWindowOutside ? H - 0.6 : 1.2; // Full height window if outside
  const windowY = hasWindowOutside ? H / 2 : H / 2 + 0.2;
  
  // Glass
  add(new THREE.BoxGeometry(0.04, windowH, windowW), windowMat, -W / 2 + 0.05, windowY, windowZ, 0, 0, 0, false, "Jendela", hasWindowOutside ? "Jendela kaca besar menghadap luar untuk sirkulasi & cahaya maksimal." : "Jendela untuk sirkulasi udara.");
  // Outer frame
  add(new THREE.BoxGeometry(0.08, 0.04, windowW + 0.08), frameMat, -W / 2 + 0.05, windowY + windowH / 2 + 0.02, windowZ, 0, 0, 0, false);
  add(new THREE.BoxGeometry(0.08, 0.04, windowW + 0.08), frameMat, -W / 2 + 0.05, windowY - windowH / 2 - 0.02, windowZ, 0, 0, 0, false);
  add(new THREE.BoxGeometry(0.08, windowH, 0.04), frameMat, -W / 2 + 0.05, windowY, windowZ + windowW / 2 + 0.02, 0, 0, 0, false);
  add(new THREE.BoxGeometry(0.08, windowH, 0.04), frameMat, -W / 2 + 0.05, windowY, windowZ - windowW / 2 - 0.02, 0, 0, 0, false);
  
  if (!hasWindowOutside) {
      // Middle divider only for standard windows
      add(new THREE.BoxGeometry(0.06, windowH, 0.02), frameMat, -W / 2 + 0.05, windowY, windowZ, 0, 0, 0, false);
  }

  // Sunlight Patch (Simulated beam on floor)
  const sunPatch = new THREE.Mesh(
    new THREE.PlaneGeometry(windowW * 1.2, D * 0.8),
    new THREE.MeshBasicMaterial({ color: 0xfff8e0, transparent: true, opacity: 0.07, side: THREE.DoubleSide })
  );
  sunPatch.position.set(-W / 4, 0.015, windowZ + D / 4);
  sunPatch.rotation.x = -Math.PI / 2;
  sunPatch.rotation.z = 0.2;
  scene.add(sunPatch);

  // 7. ENTRANCE DOOR (Right wall towards front)
  const doorZ = D / 4;
  const doorW = 0.9;
  const doorH = 2.1;
  const doorX = W / 2 - 0.05;
  
  // Door body
  const doorMesh = add(new THREE.BoxGeometry(0.04, doorH, doorW), doorMat, doorX, doorH / 2, doorZ, 0, 0, 0, true, "Pintu Masuk", "Pintu utama kamar dengan gagang pintu kuningan.");
  // Handle
  add(new THREE.SphereGeometry(0.04, 16, 16), goldMat, doorX - 0.06, doorH / 2, doorZ - doorW / 2 + 0.15);
  // Door frame
  add(new THREE.BoxGeometry(0.08, 0.06, doorW + 0.08), frameMat, doorX, doorH + 0.03, doorZ, 0, 0, 0, false);
  add(new THREE.BoxGeometry(0.08, doorH, 0.06), frameMat, doorX, doorH / 2, doorZ + doorW / 2 + 0.03, 0, 0, 0, false);
  add(new THREE.BoxGeometry(0.08, doorH, 0.06), frameMat, doorX, doorH / 2, doorZ - doorW / 2 - 0.03, 0, 0, 0, false);

  // 8. CONDITIONAL FURNITURE

  // --- A. BED (KASUR) ---
  // Positioned along the back wall, right corner
  const bedGroup = createGroup(0, 0, 0, "Tempat Tidur", "Fasilitas Kasur & Rangka Tempat Tidur yang nyaman.");
  
  // Bed frame size adapts to double/single
  let bedWidth = 1.3; // single
  const bedLength = 2.0;
  
  const tipeKamar = String(roomData.tipe || '').toLowerCase();
  if (tipeKamar.includes('double') || tipeKamar.includes('vip') || tipeKamar.includes('vvip') || facilities.some(f => f.includes('queen') || f.includes('double') || f.includes('king'))) {
    bedWidth = 1.7; // Double / VIP bed
    bedGroup.userData.desc = "Fasilitas Kasur Ukuran Double/Queen nyaman untuk kapasitas lebih besar.";
  }

  const bedX = W / 2 - bedWidth / 2 - 0.15;
  const bedZ = -D / 2 + bedLength / 2 + 0.15;

  // Frame
  const fMesh = new THREE.Mesh(new THREE.BoxGeometry(bedWidth, 0.3, bedLength), bedFrameMat);
  fMesh.position.set(bedX, 0.15, bedZ);
  fMesh.castShadow = true;
  fMesh.receiveShadow = true;
  bedGroup.add(fMesh);
  
  // Headboard
  const hbMesh = new THREE.Mesh(new THREE.BoxGeometry(bedWidth, 0.85, 0.12), bedFrameMat);
  hbMesh.position.set(bedX, 0.425, bedZ - bedLength / 2 + 0.06);
  hbMesh.castShadow = true;
  hbMesh.receiveShadow = true;
  bedGroup.add(hbMesh);

  // Mattress
  const mMesh = new THREE.Mesh(new THREE.BoxGeometry(bedWidth - 0.08, 0.22, bedLength - 0.1), mattressMat);
  mMesh.position.set(bedX, 0.35, bedZ + 0.02);
  mMesh.castShadow = true;
  mMesh.receiveShadow = true;
  bedGroup.add(mMesh);

  // Pillows
  if (bedWidth > 1.5) {
    // Two pillows
    const p1 = new THREE.Mesh(new THREE.BoxGeometry(0.55, 0.12, 0.38), pillowMat);
    p1.position.set(bedX - 0.32, 0.48, bedZ - bedLength / 2 + 0.3);
    p1.rotation.x = 0.08;
    p1.castShadow = true;
    bedGroup.add(p1);

    const p2 = new THREE.Mesh(new THREE.BoxGeometry(0.55, 0.12, 0.38), pillowMat);
    p2.position.set(bedX + 0.32, 0.48, bedZ - bedLength / 2 + 0.3);
    p2.rotation.x = 0.08;
    p2.castShadow = true;
    bedGroup.add(p2);
  } else {
    // One pillow
    const p1 = new THREE.Mesh(new THREE.BoxGeometry(0.65, 0.12, 0.38), pillowMat);
    p1.position.set(bedX, 0.48, bedZ - bedLength / 2 + 0.3);
    p1.rotation.x = 0.08;
    p1.castShadow = true;
    bedGroup.add(p1);
  }

  // Blanket
  const bMesh = new THREE.Mesh(new THREE.BoxGeometry(bedWidth - 0.06, 0.1, bedLength * 0.65), blanketMat);
  bMesh.position.set(bedX, 0.44, bedZ + bedLength * 0.17);
  bMesh.castShadow = true;
  bedGroup.add(bMesh);

  // Add all children of bedGroup to clickable list
  bedGroup.children.forEach(c => {
    c.userData = { name: bedGroup.userData.name, desc: bedGroup.userData.desc };
    clickableObjects.push(c);
  });


  // --- B. STUDY DESK & CHAIR (MEJA BELAJAR & KURSI) ---
  if (hasDesk) {
    const deskGroup = createGroup(0, 0, 0, "Meja & Kursi Belajar", "Meja belajar kayu jati lengkap dengan laptop dan rak buku.");
    
    // Positioned along the left wall (near window)
    const deskX = -W / 2 + 0.45;
    const deskZ = -D / 8;
    const deskW = 1.3;
    const deskD = 0.65;
    const deskH = 0.78;

    // Tabletop
    const top = new THREE.Mesh(new THREE.BoxGeometry(deskW, 0.05, deskD), deskMat);
    top.position.set(deskX, deskH - 0.025, deskZ);
    top.castShadow = true;
    top.receiveShadow = true;
    deskGroup.add(top);

    // Legs
    const legG = new THREE.BoxGeometry(0.05, deskH - 0.05, 0.05);
    const legOffsetW = deskW / 2 - 0.04;
    const legOffsetD = deskD / 2 - 0.04;

    const leg1 = new THREE.Mesh(legG, legMat); leg1.position.set(deskX - legOffsetW, (deskH - 0.05) / 2, deskZ - legOffsetD); deskGroup.add(leg1);
    const leg2 = new THREE.Mesh(legG, legMat); leg2.position.set(deskX + legOffsetW, (deskH - 0.05) / 2, deskZ - legOffsetD); deskGroup.add(leg2);
    const leg3 = new THREE.Mesh(legG, legMat); leg3.position.set(deskX - legOffsetW, (deskH - 0.05) / 2, deskZ + legOffsetD); deskGroup.add(leg3);
    const leg4 = new THREE.Mesh(legG, legMat); leg4.position.set(deskX + legOffsetW, (deskH - 0.05) / 2, deskZ + legOffsetD); deskGroup.add(leg4);

    // Shelf/Rack
    const shelfY = deskH + 0.55;
    const shelf = new THREE.Mesh(new THREE.BoxGeometry(deskW - 0.1, 0.03, 0.24), deskMat);
    shelf.position.set(deskX, shelfY, deskZ - deskD / 2 + 0.14);
    shelf.castShadow = true;
    deskGroup.add(shelf);

    // Shelf brackets
    const b1 = new THREE.Mesh(new THREE.BoxGeometry(0.03, 0.5, 0.03), legMat);
    b1.position.set(deskX - deskW / 3, deskH + 0.25, deskZ - deskD / 2 + 0.03);
    deskGroup.add(b1);
    const b2 = new THREE.Mesh(new THREE.BoxGeometry(0.03, 0.5, 0.03), legMat);
    b2.position.set(deskX + deskW / 3, deskH + 0.25, deskZ - deskD / 2 + 0.03);
    deskGroup.add(b2);

    // Books on shelf
    const colors = [0xc0392b, 0x27ae60, 0xe67e22, 0x2980b9];
    for (let i = 0; i < 4; i++) {
      const book = new THREE.Mesh(new THREE.BoxGeometry(0.06, 0.22, 0.16), M(colors[i]));
      book.position.set(deskX - 0.3 + (i * 0.08), shelfY + 0.11, deskZ - deskD / 2 + 0.14);
      book.castShadow = true;
      deskGroup.add(book);
    }

    // Laptop
    const lBody = new THREE.Mesh(new THREE.BoxGeometry(0.36, 0.015, 0.24), laptopMat);
    lBody.position.set(deskX, deskH, deskZ);
    lBody.castShadow = true;
    deskGroup.add(lBody);

    const lScreen = new THREE.Mesh(new THREE.BoxGeometry(0.36, 0.24, 0.015), screenMat);
    lScreen.position.set(deskX, deskH + 0.115, deskZ - 0.11);
    lScreen.rotation.x = 0.12;
    lScreen.castShadow = true;
    deskGroup.add(lScreen);

    // --- CHAIR (KURSI) ---
    const chairX = deskX + 0.1;
    const chairZ = deskZ + 0.72;
    const chairSeatH = 0.46;

    // Seat
    const seat = new THREE.Mesh(new THREE.BoxGeometry(0.44, 0.04, 0.44), chairMat);
    seat.position.set(chairX, chairSeatH, chairZ);
    seat.castShadow = true;
    deskGroup.add(seat);

    // Backrest
    const back = new THREE.Mesh(new THREE.BoxGeometry(0.44, 0.38, 0.04), chairMat);
    back.position.set(chairX, chairSeatH + 0.2, chairZ + 0.2);
    back.castShadow = true;
    deskGroup.add(back);

    // Chair legs
    const cLegG = new THREE.BoxGeometry(0.04, chairSeatH - 0.02, 0.04);
    const cl1 = new THREE.Mesh(cLegG, legMat); cl1.position.set(chairX - 0.17, chairSeatH / 2, chairZ - 0.17); deskGroup.add(cl1);
    const cl2 = new THREE.Mesh(cLegG, legMat); cl2.position.set(chairX + 0.17, chairSeatH / 2, chairZ - 0.17); deskGroup.add(cl2);
    const cl3 = new THREE.Mesh(cLegG, legMat); cl3.position.set(chairX - 0.17, chairSeatH / 2, chairZ + 0.17); deskGroup.add(cl3);
    const cl4 = new THREE.Mesh(cLegG, legMat); cl4.position.set(chairX + 0.17, chairSeatH / 2, chairZ + 0.17); deskGroup.add(cl4);

    // Add all children of deskGroup to clickable list
    deskGroup.children.forEach(c => {
      c.userData = { name: deskGroup.userData.name, desc: deskGroup.userData.desc };
      clickableObjects.push(c);
    });
  }


  // --- C. WARDROBE (LEMARI PAKAIAN) ---
  if (hasWardrobe) {
    const wardrobeGroup = createGroup(0, 0, 0, "Lemari Pakaian", "Lemari penyimpanan bergaya modern minimalis.");
    
    // Positioned along the back wall, left side
    const wardW = 1.0;
    const wardH = 2.1;
    const wardD = 0.55;
    
    const wardX = -W / 2 + wardW / 2 + 0.2;
    const wardZ = -D / 2 + wardD / 2 + 0.06;

    // Main cabinet body (Wood outer shell)
    const body = new THREE.Mesh(new THREE.BoxGeometry(wardW, wardH, wardD), woodAccentMat);
    body.position.set(wardX, wardH / 2, wardZ);
    body.castShadow = true;
    body.receiveShadow = true;
    wardrobeGroup.add(body);

    // Front Doors (White clean doors)
    const doorL = new THREE.Mesh(new THREE.BoxGeometry(wardW / 2 - 0.01, wardH - 0.06, 0.02), wardrobeMat);
    doorL.position.set(wardX - wardW / 4, wardH / 2, wardZ + wardD / 2 + 0.01);
    wardrobeGroup.add(doorL);
    
    const doorR = new THREE.Mesh(new THREE.BoxGeometry(wardW / 2 - 0.01, wardH - 0.06, 0.02), wardrobeMat);
    doorR.position.set(wardX + wardW / 4, wardH / 2, wardZ + wardD / 2 + 0.01);
    wardrobeGroup.add(doorR);

    // Modern Long Minimalist Handles
    const handleL = new THREE.Mesh(new THREE.BoxGeometry(0.02, 0.6, 0.02), goldMat);
    handleL.position.set(wardX - 0.04, wardH / 2, wardZ + wardD / 2 + 0.025);
    wardrobeGroup.add(handleL);

    const handleR = new THREE.Mesh(new THREE.BoxGeometry(0.02, 0.6, 0.02), goldMat);
    handleR.position.set(wardX + 0.04, wardH / 2, wardZ + wardD / 2 + 0.025);
    wardrobeGroup.add(handleR);

    // Add all children of wardrobeGroup to clickable list
    wardrobeGroup.children.forEach(c => {
      c.userData = { name: wardrobeGroup.userData.name, desc: wardrobeGroup.userData.desc };
      clickableObjects.push(c);
    });
  }


  // --- D. AIR CONDITIONER (AC SPLIT) ---
  if (hasAC) {
    const acGroup = createGroup(0, 0, 0, "AC Split", "Pendingin ruangan (AC) hemat energi untuk menjaga kenyamanan kamar.");

    const acW = 0.76;
    const acH = 0.22;
    const acD = 0.18;
    // Positioned high up on the back wall above the desk
    const acX = -W / 2 + acW / 2 + 0.3;
    const acY = H - 0.4;
    const acZ = -D / 2 + acD / 2 + 0.06;

    // AC Body
    const body = new THREE.Mesh(new THREE.BoxGeometry(acW, acH, acD), M(0xffffff, { roughness: 0.3 }));
    body.position.set(acX, acY, acZ);
    body.castShadow = true;
    acGroup.add(body);

    // Air Vent panel
    const vent = new THREE.Mesh(new THREE.BoxGeometry(acW - 0.08, 0.025, acD + 0.01), plasticMat);
    vent.position.set(acX, acY - acH / 2 + 0.02, acZ);
    acGroup.add(vent);

    // Small LED status light
    const led = new THREE.Mesh(new THREE.BoxGeometry(0.02, 0.02, 0.02), M(0x4ade80, { emissive: 0x4ade80, emissiveIntensity: 0.8 }));
    led.position.set(acX + acW / 2 - 0.06, acY - 0.04, acZ + acD / 2 + 0.005);
    acGroup.add(led);

    // Add all children of acGroup to clickable list
    acGroup.children.forEach(c => {
      c.userData = { name: acGroup.userData.name, desc: acGroup.userData.desc };
      clickableObjects.push(c);
    });
  }


  // --- E. FLATSCREEN TV (WALL MOUNTED) ---
  if (hasTV) {
    const tvGroup = createGroup(0, 0, 0, "Smart TV", "Fasilitas hiburan Smart TV layar datar terpasang di dinding.");

    const tvW = 1.0;
    const tvH = 0.58;
    const tvD = 0.04;
    // Mounted opposite to the bed
    const tvX = -W / 2 + 0.06;
    const tvY = 1.35;
    const tvZ = D / 4;

    // TV Body (Frame)
    const tvFrame = new THREE.Mesh(new THREE.BoxGeometry(tvD, tvH, tvW), plasticMat);
    tvFrame.position.set(tvX, tvY, tvZ);
    tvFrame.castShadow = true;
    tvGroup.add(tvFrame);

    // Screen
    const tvScreen = new THREE.Mesh(new THREE.BoxGeometry(0.01, tvH - 0.04, tvW - 0.06), M(0x0a0c10, { roughness: 0.1, metalness: 0.9 }));
    tvScreen.position.set(tvX - 0.018, tvY, tvZ);
    tvGroup.add(tvScreen);

    // Wall mount bracket (Behind TV)
    const bracket = new THREE.Mesh(new THREE.BoxGeometry(0.06, 0.3, 0.3), legMat);
    bracket.position.set(tvX + 0.025, tvY, tvZ);
    tvGroup.add(bracket);

    tvGroup.children.forEach(c => {
      c.userData = { name: tvGroup.userData.name, desc: tvGroup.userData.desc };
      clickableObjects.push(c);
    });
  }


  // --- F. EN-SUITE BATHROOM DOOR (KAMAR MANDI DALAM) ---
  if (hasEnsuiteBathroom) {
    const bmGroup = createGroup(0, 0, 0, "Kamar Mandi Dalam", "Kamar mandi pribadi yang bersih di dalam kamar.");

    // Let's draw an en-suite door on the BACK wall, near the middle-left
    const bmZ = -D / 2 + 0.04;
    const bmDoorW = 0.76;
    const bmDoorH = 1.95;
    const bmDoorX = -0.3; // slightly left of center

    // Door body (Facing forward now: width is on X axis, depth is on Z axis)
    const door = new THREE.Mesh(new THREE.BoxGeometry(bmDoorW, bmDoorH, 0.04), M(0xdddddd, { roughness: 0.3 })); // PVC door
    door.position.set(bmDoorX, bmDoorH / 2, bmZ);
    door.castShadow = true;
    bmGroup.add(door);

    // Label indicator or sign (little plastic plate)
    const sign = new THREE.Mesh(new THREE.BoxGeometry(0.18, 0.12, 0.02), M(0x3b82f6));
    sign.position.set(bmDoorX, 1.45, bmZ + 0.02);
    bmGroup.add(sign);

    // Handle
    const handle = new THREE.Mesh(new THREE.SphereGeometry(0.035, 12, 12), goldMat);
    handle.position.set(bmDoorX + bmDoorW / 2 - 0.12, bmDoorH / 2, bmZ + 0.03);
    bmGroup.add(handle);

    bmGroup.children.forEach(c => {
      c.userData = { name: bmGroup.userData.name, desc: bmGroup.userData.desc };
      clickableObjects.push(c);
    });
  }


  // --- F1. MINI FRIDGE (KULKAS MINI) ---
  if (hasFridge) {
    const fridgeGroup = createGroup(0, 0, 0, "Kulkas Mini", "Fasilitas kulkas mini untuk menyimpan makanan dan minuman.");
    
    const fW = 0.45;
    const fH = 0.8;
    const fD = 0.45;
    // Position near the door or desk
    const fX = W / 2 - fW / 2 - 0.1;
    const fZ = D / 2 - fD / 2 - 0.1;

    // Fridge body
    const fBody = new THREE.Mesh(new THREE.BoxGeometry(fW, fH, fD), M(0xe0e0e0, { metalness: 0.3, roughness: 0.5 }));
    fBody.position.set(fX, fH / 2, fZ);
    fBody.castShadow = true;
    fridgeGroup.add(fBody);

    // Fridge Door
    const fDoor = new THREE.Mesh(new THREE.BoxGeometry(fW + 0.02, fH - 0.04, 0.04), M(0xf0f0f0, { metalness: 0.4, roughness: 0.3 }));
    fDoor.position.set(fX, fH / 2, fZ - fD / 2 - 0.01);
    fridgeGroup.add(fDoor);

    // Handle
    const fHandle = new THREE.Mesh(new THREE.BoxGeometry(0.02, 0.15, 0.03), M(0xaaaaaa, { metalness: 0.8 }));
    fHandle.position.set(fX - fW / 2 + 0.06, fH - 0.15, fZ - fD / 2 - 0.03);
    fridgeGroup.add(fHandle);

    fridgeGroup.children.forEach(c => {
      c.userData = { name: fridgeGroup.userData.name, desc: fridgeGroup.userData.desc };
      clickableObjects.push(c);
    });
  }

  // --- F2. STANDING FAN (KIPAS ANGIN) ---
  if (hasFan) {
    const fanGroup = createGroup(0, 0, 0, "Kipas Angin", "Kipas angin berdiri untuk sirkulasi udara.");
    
    // Position in a free corner
    const fanX = -W / 2 + 0.3;
    const fanZ = D / 2 - 0.3;

    // Base
    const fBase = new THREE.Mesh(new THREE.CylinderGeometry(0.18, 0.2, 0.04, 16), plasticMat);
    fBase.position.set(fanX, 0.02, fanZ);
    fBase.castShadow = true;
    fanGroup.add(fBase);

    // Stand / Pole
    const fPole = new THREE.Mesh(new THREE.CylinderGeometry(0.02, 0.02, 0.8, 8), M(0xcccccc, { metalness: 0.8 }));
    fPole.position.set(fanX, 0.42, fanZ);
    fPole.castShadow = true;
    fanGroup.add(fPole);

    // Motor housing
    const fMotor = new THREE.Mesh(new THREE.BoxGeometry(0.12, 0.12, 0.16), plasticMat);
    fMotor.position.set(fanX, 0.85, fanZ);
    fanGroup.add(fMotor);

    // Blade Guard (Grill)
    const fGuard = new THREE.Mesh(new THREE.CylinderGeometry(0.22, 0.22, 0.04, 16), M(0xdddddd, { wireframe: true }));
    fGuard.rotation.x = Math.PI / 2;
    fGuard.position.set(fanX, 0.85, fanZ - 0.1);
    fanGroup.add(fGuard);

    fanGroup.children.forEach(c => {
      c.userData = { name: fanGroup.userData.name, desc: fanGroup.userData.desc };
      clickableObjects.push(c);
    });
  }

  // --- G. RUG & CEILING FIXTURES (DECORATIVE) ---
  // Modern Round Rug in the center area
  const rugGroup = createGroup(0, 0, 0, "Karpet Kamar", "Karpet bulat modern yang estetik.");
  const rugRadius = Math.min(W, D) * 0.35;
  const rugX = 0;
  const rugZ = D / 8;

  const rMain = new THREE.Mesh(new THREE.CylinderGeometry(rugRadius, rugRadius, 0.01, 32), rugMat); 
  rMain.position.set(rugX, 0.005, rugZ); 
  rMain.receiveShadow = true; 
  rugGroup.add(rMain);
  
  const rInner = new THREE.Mesh(new THREE.CylinderGeometry(rugRadius - 0.1, rugRadius - 0.1, 0.012, 32), rugPatMat); 
  rInner.position.set(rugX, 0.007, rugZ); 
  rInner.receiveShadow = true; 
  rugGroup.add(rInner);

  rugGroup.children.forEach(c => {
    c.userData = { name: rugGroup.userData.name, desc: rugGroup.userData.desc };
    clickableObjects.push(c);
  });

  // Ceiling lamp (Modern LED Panel)
  add(new THREE.CylinderGeometry(0.25, 0.25, 0.04, 32), lightFixtureMat, 0, H - 0.02, 0, 0, 0, 0, false);


  // 9. CAMERA & NAVIGATION SYSTEM (CUSTOM ORBIT CONTROLS)
  let isDragging = false;
  let prevMouse = { x: 0, y: 0 };
  const sph = { theta: 0.65, phi: 0.95, r: Math.max(W, D) * 1.6 };
  const target = new THREE.Vector3(0, H / 2.5, 0);

  const updateCamera = () => {
    camera.position.set(
      target.x + sph.r * Math.sin(sph.phi) * Math.sin(sph.theta),
      target.y + sph.r * Math.cos(sph.phi),
      target.z + sph.r * Math.sin(sph.phi) * Math.cos(sph.theta)
    );
    camera.lookAt(target);
  };
  updateCamera();

  // Mouse Interactions
  const onDown = (e) => {
    isDragging = true;
    prevMouse = { x: e.clientX, y: e.clientY };
  };

  const onMove = (e) => {
    if (!isDragging) return;
    const deltaX = e.clientX - prevMouse.x;
    const deltaY = e.clientY - prevMouse.y;
    
    sph.theta -= deltaX * 0.006;
    sph.phi = Math.max(0.12, Math.min(1.48, sph.phi - deltaY * 0.006));
    
    prevMouse = { x: e.clientX, y: e.clientY };
    updateCamera();
  };

  const onUp = () => {
    isDragging = false;
  };

  const onWheel = (e) => {
    e.preventDefault();
    const minZoom = Math.max(W, D) * 0.85;
    const maxZoom = Math.max(W, D) * 2.8;
    sph.r = Math.max(minZoom, Math.min(maxZoom, sph.r + e.deltaY * 0.006));
    updateCamera();
  };

  // Touch Interactions for Mobile
  const onTouchStart = (e) => {
    if (e.touches.length === 1) {
      isDragging = true;
      prevMouse = { x: e.touches[0].clientX, y: e.touches[0].clientY };
    }
  };

  const onTouchMove = (e) => {
    if (!isDragging || e.touches.length !== 1) return;
    const deltaX = e.touches[0].clientX - prevMouse.x;
    const deltaY = e.touches[0].clientY - prevMouse.y;

    sph.theta -= deltaX * 0.008;
    sph.phi = Math.max(0.12, Math.min(1.48, sph.phi - deltaY * 0.008));

    prevMouse = { x: e.touches[0].clientX, y: e.touches[0].clientY };
    updateCamera();
  };

  // Attach Event Listeners
  renderer.domElement.style.touchAction = 'none'; // Prevents scrolling on touch drag
  
  renderer.domElement.addEventListener("mousedown", onDown);
  window.addEventListener("mousemove", onMove);
  window.addEventListener("mouseup", onUp);
  renderer.domElement.addEventListener("wheel", onWheel, { passive: false });

  renderer.domElement.addEventListener("touchstart", onTouchStart, { passive: true });
  renderer.domElement.addEventListener("touchmove", onTouchMove, { passive: true });
  renderer.domElement.addEventListener("touchend", onUp);

  // 10. TOOLTIP RAYCASTER SYSTEM (CLICK TO INSPECT)
  const raycaster = new THREE.Raycaster();
  const mouse = new THREE.Vector2();

  // Floating tooltip element creation inside the container (absolute positioned)
  const tooltip = document.createElement('div');
  tooltip.style.position = 'absolute';
  tooltip.style.padding = '8px 12px';
  tooltip.style.background = 'rgba(15, 23, 42, 0.9)';
  tooltip.style.border = '1px solid rgba(255, 255, 255, 0.15)';
  tooltip.style.borderRadius = '8px';
  tooltip.style.color = '#fff';
  tooltip.style.fontSize = '12px';
  tooltip.style.fontWeight = '500';
  tooltip.style.pointerEvents = 'none';
  tooltip.style.opacity = '0';
  tooltip.style.transition = 'opacity 0.2s, transform 0.2s';
  tooltip.style.transform = 'translate(-50%, -125%)';
  tooltip.style.zIndex = '100';
  tooltip.style.boxShadow = '0 10px 15px -3px rgba(0, 0, 0, 0.5)';
  tooltip.style.display = 'none';
  container.appendChild(tooltip);

  const handleInspect = (e) => {
    // Standard click coordinates relative to canvas
    const rect = renderer.domElement.getBoundingClientRect();
    const clientX = e.clientX || (e.touches && e.touches[0].clientX);
    const clientY = e.clientY || (e.touches && e.touches[0].clientY);

    if (clientX === undefined || clientY === undefined) return;

    mouse.x = ((clientX - rect.left) / rect.width) * 2 - 1;
    mouse.y = -((clientY - rect.top) / rect.height) * 2 + 1;

    raycaster.setFromCamera(mouse, camera);
    const intersects = raycaster.intersectObjects(clickableObjects, true);

    if (intersects.length > 0) {
      const hitObj = intersects[0].object;
      const data = hitObj.userData;
      if (data && data.name) {
        // Show tooltip
        tooltip.innerHTML = `<strong style="color: #f59e0b; display: block; margin-bottom: 2px;">${data.name}</strong><span>${data.desc}</span>`;
        tooltip.style.display = 'block';
        
        // Position relative to the container
        const localX = clientX - rect.left;
        const localY = clientY - rect.top;
        
        tooltip.style.left = `${localX}px`;
        tooltip.style.top = `${localY}px`;
        
        setTimeout(() => {
          tooltip.style.opacity = '1';
        }, 10);
        return;
      }
    }

    // Hide tooltip if clicked empty space
    tooltip.style.opacity = '0';
    setTimeout(() => {
      tooltip.style.display = 'none';
    }, 200);
  };

  renderer.domElement.addEventListener("click", handleInspect);

  // 11. DAY/NIGHT TOGGLE CONTROLLER
  let isNightMode = false;
  
  const setDayNightMode = (isNight) => {
    isNightMode = isNight;
    if (isNight) {
      scene.background = new THREE.Color(0x0a0c16);
      sunLight.intensity = 0.0;
      sunPatch.material.opacity = 0.0;
      ambient.intensity = 0.12;
      ambient.color.setHex(0x3a4b6e);
      ceilingLight.intensity = 1.5;
      ceilingLight.color.setHex(0xffaa44); // Warm bulb glow
      windowGlow.color.setHex(0x1a2536);
      windowGlow.intensity = 0.25;
    } else {
      scene.background = new THREE.Color(0x1a1f2e);
      sunLight.intensity = 1.4;
      sunPatch.material.opacity = 0.07;
      ambient.intensity = 0.6;
      ambient.color.setHex(0xfff5e0);
      ceilingLight.intensity = 0.9;
      ceilingLight.color.setHex(0xfff0cc);
      windowGlow.color.setHex(0x87ceeb);
      windowGlow.intensity = 0.8;
    }
  };

  // 12. ANIMATION & RESIZE LOOPS
  const handleResize = () => {
    const w = container.clientWidth;
    const h = container.clientHeight || 400;
    camera.aspect = w / h;
    camera.updateProjectionMatrix();
    renderer.setSize(w, h);
  };
  window.addEventListener("resize", handleResize);

  const clock = new THREE.Clock();
  let animId;
  const animate = () => {
    animId = requestAnimationFrame(animate);
    const t = clock.getElapsedTime();
    
    // Ambient micro-flicker or ceiling glow pulsing
    if (isNightMode) {
      ceilingLight.intensity = 1.4 + Math.sin(t * 0.6) * 0.04;
    } else {
      ceilingLight.intensity = 0.8 + Math.sin(t * 0.3) * 0.03;
    }

    renderer.render(scene, camera);
  };
  animate();

  // 13. EXPOSE DISPOSAL & CONTROL FUNCTIONS
  return {
    setDayNightMode,
    destroy: () => {
      cancelAnimationFrame(animId);
      renderer.domElement.removeEventListener("mousedown", onDown);
      window.removeEventListener("mousemove", onMove);
      window.removeEventListener("mouseup", onUp);
      renderer.domElement.removeEventListener("wheel", onWheel);
      renderer.domElement.removeEventListener("touchstart", onTouchStart);
      renderer.domElement.removeEventListener("touchmove", onTouchMove);
      renderer.domElement.removeEventListener("touchend", onUp);
      renderer.domElement.removeEventListener("click", handleInspect);
      window.removeEventListener("resize", handleResize);
      renderer.dispose();
      if (container.contains(renderer.domElement)) {
        container.removeChild(renderer.domElement);
      }
    }
  };
}
