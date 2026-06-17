import { useEffect, useRef, useState } from "react";
import * as THREE from "three";

export default function RoomViewer() {
  const mountRef = useRef(null);
  const [activeTab, setActiveTab] = useState("3d");

  useEffect(() => {
    if (activeTab !== "3d") return;
    const container = mountRef.current;
    if (!container) return;
    const width = container.clientWidth;
    const height = container.clientHeight;

    // Scene
    const scene = new THREE.Scene();
    scene.background = new THREE.Color(0x1a1f2e);

    // Camera
    const camera = new THREE.PerspectiveCamera(55, width / height, 0.1, 100);

    // Renderer
    const renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setSize(width, height);
    renderer.setPixelRatio(window.devicePixelRatio);
    renderer.shadowMap.enabled = true;
    renderer.shadowMap.type = THREE.PCFSoftShadowMap;
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1.1;
    container.appendChild(renderer.domElement);

    // Lighting
    const ambient = new THREE.AmbientLight(0xfff5e0, 0.6);
    scene.add(ambient);

    const sunLight = new THREE.DirectionalLight(0xfff8e7, 1.4);
    sunLight.position.set(3, 6, 4);
    sunLight.castShadow = true;
    sunLight.shadow.mapSize.set(2048, 2048);
    sunLight.shadow.camera.near = 0.5;
    sunLight.shadow.camera.far = 20;
    sunLight.shadow.camera.left = -6;
    sunLight.shadow.camera.right = 6;
    sunLight.shadow.camera.top = 6;
    sunLight.shadow.camera.bottom = -6;
    scene.add(sunLight);

    const ceilingLight = new THREE.PointLight(0xfff0cc, 1.2, 8);
    ceilingLight.position.set(0, 2.7, 0);
    ceilingLight.castShadow = false;
    scene.add(ceilingLight);

    const windowGlow = new THREE.PointLight(0x87ceeb, 0.8, 5);
    windowGlow.position.set(-1.5, 1.8, -2.5);
    scene.add(windowGlow);

    // Materials
    const M = (color, opts = {}) => new THREE.MeshLambertMaterial({ color, ...opts });
    const wallMat    = M(0xf2ece0);
    const floorMat   = M(0xb07c3a);
    const ceilMat    = M(0xfaf8f5);
    const bedFrameMat = M(0x4a2e1a);
    const mattressMat = M(0xe8e0d0);
    const pillowMat  = M(0xffffff);
    const blanketMat = M(0x3d7abf);
    const deskMat    = M(0x7a5230);
    const legMat     = M(0x4a2e1a);
    const windowMat  = M(0xa8d8f0, { transparent: true, opacity: 0.55 });
    const frameMat   = M(0xfafafa);
    const doorMat    = M(0x7a5230);
    const wardrobeMat = M(0x5a3a1a);
    const goldMat    = M(0xe8b84b);
    const rugMat     = M(0x7c3a6e);
    const rugPatMat  = M(0xb05a9e);
    const chairMat   = M(0x2a5c2e);
    const laptopMat  = M(0x555555);
    const screenMat  = M(0x223344, { emissive: 0x112233, emissiveIntensity: 0.4 });
    const baseboardMat = M(0xddd0ba);
    const lightFixtureMat = M(0xf5f5f0, { emissive: 0xfff5c0, emissiveIntensity: 0.6 });

    const add = (geo, mat, x, y, z, rx = 0, ry = 0, rz = 0, shadow = true) => {
      const mesh = new THREE.Mesh(geo, mat);
      mesh.position.set(x, y, z);
      mesh.rotation.set(rx, ry, rz);
      if (shadow) { mesh.castShadow = true; mesh.receiveShadow = true; }
      scene.add(mesh);
      return mesh;
    };

    // === ROOM SHELL ===
    const W = 6, H = 3, D = 6;
    add(new THREE.BoxGeometry(W, 0.12, D), floorMat, 0, -0.06, 0);
    add(new THREE.BoxGeometry(W, 0.08, D), ceilMat, 0, H + 0.04, 0);
    add(new THREE.BoxGeometry(W, H, 0.1), wallMat, 0, H / 2, -D / 2);
    add(new THREE.BoxGeometry(0.1, H, D), wallMat, -W / 2, H / 2, 0);
    add(new THREE.BoxGeometry(0.1, H, D), wallMat, W / 2, H / 2, 0);

    // Baseboard trims
    add(new THREE.BoxGeometry(W, 0.1, 0.05), baseboardMat, 0, 0.05, -D / 2 + 0.1, 0, 0, 0, false);
    add(new THREE.BoxGeometry(0.05, 0.1, D), baseboardMat, -W / 2 + 0.1, 0.05, 0, 0, 0, 0, false);
    add(new THREE.BoxGeometry(0.05, 0.1, D), baseboardMat, W / 2 - 0.1, 0.05, 0, 0, 0, 0, false);

    // === WINDOW ===
    add(new THREE.BoxGeometry(1.6, 1.3, 0.06), windowMat, -1.3, 1.85, -D / 2 + 0.08, 0, 0, 0, false);
    // Frame pieces
    add(new THREE.BoxGeometry(1.76, 0.07, 0.1), frameMat, -1.3, 2.52, -D / 2 + 0.08, 0, 0, 0, false);
    add(new THREE.BoxGeometry(1.76, 0.07, 0.1), frameMat, -1.3, 1.18, -D / 2 + 0.08, 0, 0, 0, false);
    add(new THREE.BoxGeometry(0.07, 1.44, 0.1), frameMat, -2.18, 1.85, -D / 2 + 0.08, 0, 0, 0, false);
    add(new THREE.BoxGeometry(0.07, 1.44, 0.1), frameMat, -0.42, 1.85, -D / 2 + 0.08, 0, 0, 0, false);
    // Middle bar
    add(new THREE.BoxGeometry(0.04, 1.44, 0.07), frameMat, -1.3, 1.85, -D / 2 + 0.06, 0, 0, 0, false);

    // Sunlight rect behind window (simulated)
    const sunPatch = new THREE.Mesh(
      new THREE.PlaneGeometry(1.5, 2.5),
      new THREE.MeshBasicMaterial({ color: 0xfff8e0, transparent: true, opacity: 0.07 })
    );
    sunPatch.position.set(-1.3, 0.5, -D / 2 + 0.5);
    sunPatch.rotation.x = -Math.PI / 2.5;
    scene.add(sunPatch);

    // === DOOR ===
    add(new THREE.BoxGeometry(0.9, 2.1, 0.07), doorMat, 1.7, 1.05, -D / 2 + 0.08);
    add(new THREE.SphereGeometry(0.045, 8, 8), goldMat, 1.32, 1.05, -D / 2 + 0.15);
    // Door frame
    add(new THREE.BoxGeometry(0.96, 0.07, 0.1), frameMat, 1.7, 2.12, -D / 2 + 0.08, 0, 0, 0, false);
    add(new THREE.BoxGeometry(0.07, 2.15, 0.1), frameMat, 1.245, 1.05, -D / 2 + 0.08, 0, 0, 0, false);
    add(new THREE.BoxGeometry(0.07, 2.15, 0.1), frameMat, 2.155, 1.05, -D / 2 + 0.08, 0, 0, 0, false);

    // === BED ===
    add(new THREE.BoxGeometry(1.9, 0.22, 3.1), bedFrameMat, 1.85, 0.11, 0.6);
    // Headboard
    add(new THREE.BoxGeometry(1.9, 0.75, 0.14), bedFrameMat, 1.85, 0.58, -1.0);
    // Mattress
    add(new THREE.BoxGeometry(1.72, 0.2, 2.85), mattressMat, 1.85, 0.32, 0.62);
    // Pillows
    add(new THREE.BoxGeometry(0.55, 0.13, 0.38), pillowMat, 1.55, 0.46, -0.62);
    add(new THREE.BoxGeometry(0.55, 0.13, 0.38), pillowMat, 2.15, 0.46, -0.62);
    // Blanket
    add(new THREE.BoxGeometry(1.68, 0.09, 2.0), blanketMat, 1.85, 0.465, 0.82);

    // === DESK ===
    add(new THREE.BoxGeometry(1.5, 0.06, 0.72), deskMat, -1.8, 0.79, -1.55);
    [[-2.5, 0.4, -1.22], [-1.1, 0.4, -1.22], [-2.5, 0.4, -1.88], [-1.1, 0.4, -1.88]].forEach(([x, y, z]) =>
      add(new THREE.BoxGeometry(0.06, 0.82, 0.06), legMat, x, y, z)
    );
    // Desk shelf
    add(new THREE.BoxGeometry(1.5, 0.04, 0.28), deskMat, -1.8, 1.5, -1.72);
    // Books on shelf
    [[0xc0392b, -2.2, 1.62, -1.73], [0x27ae60, -2.02, 1.62, -1.73], [0xe67e22, -1.86, 1.62, -1.73]].forEach(([c, x, y, z]) =>
      add(new THREE.BoxGeometry(0.1, 0.22, 0.18), M(c), x, y, z)
    );
    // Laptop
    add(new THREE.BoxGeometry(0.38, 0.02, 0.26), laptopMat, -1.8, 0.83, -1.56);
    const screen = add(new THREE.BoxGeometry(0.38, 0.25, 0.02), screenMat, -1.8, 0.955, -1.44);
    screen.rotation.x = -0.25;

    // === CHAIR ===
    add(new THREE.BoxGeometry(0.52, 0.06, 0.52), chairMat, -1.8, 0.49, -0.72);
    add(new THREE.BoxGeometry(0.52, 0.42, 0.05), chairMat, -1.8, 0.71, -0.97);
    [[-2.0, 0.24, -0.5], [-1.6, 0.24, -0.5], [-2.0, 0.24, -0.93], [-1.6, 0.24, -0.93]].forEach(([x, y, z]) =>
      add(new THREE.BoxGeometry(0.045, 0.48, 0.045), legMat, x, y, z)
    );

    // === WARDROBE ===
    add(new THREE.BoxGeometry(1.3, 2.3, 0.58), wardrobeMat, 1.85, 1.15, -2.46);
    add(new THREE.BoxGeometry(0.02, 2.28, 0.02), M(0x6b4c2a), 1.85, 1.15, -2.16, 0, 0, 0, false);
    add(new THREE.BoxGeometry(0.04, 0.18, 0.05), goldMat, 1.56, 1.15, -2.16, 0, 0, 0, false);
    add(new THREE.BoxGeometry(0.04, 0.18, 0.05), goldMat, 2.14, 1.15, -2.16, 0, 0, 0, false);

    // === RUG ===
    add(new THREE.BoxGeometry(3.2, 0.025, 2.6), rugMat, 0.2, 0.012, 0.6, 0, 0, 0, false);
    // Rug border pattern
    add(new THREE.BoxGeometry(2.9, 0.026, 2.3), rugPatMat, 0.2, 0.014, 0.6, 0, 0, 0, false);
    add(new THREE.BoxGeometry(2.6, 0.027, 2.0), rugMat, 0.2, 0.016, 0.6, 0, 0, 0, false);

    // === CEILING LIGHT ===
    add(new THREE.CylinderGeometry(0.12, 0.18, 0.12, 12), lightFixtureMat, 0, H - 0.06, 0, 0, 0, 0, false);
    add(new THREE.CylinderGeometry(0.015, 0.015, 0.15, 8), M(0xcccccc), 0, H - 0.2, 0, 0, 0, 0, false);

    // === ORBIT CONTROLS ===
    let isDragging = false;
    let prevMouse = { x: 0, y: 0 };
    const sph = { theta: 0.65, phi: 0.78, r: 8.5 };
    const target = new THREE.Vector3(0, 1.1, -0.3);

    const updateCamera = () => {
      camera.position.set(
        target.x + sph.r * Math.sin(sph.phi) * Math.sin(sph.theta),
        target.y + sph.r * Math.cos(sph.phi),
        target.z + sph.r * Math.sin(sph.phi) * Math.cos(sph.theta)
      );
      camera.lookAt(target);
    };
    updateCamera();

    const onDown = (e) => { isDragging = true; prevMouse = { x: e.clientX, y: e.clientY }; };
    const onMove = (e) => {
      if (!isDragging) return;
      sph.theta -= (e.clientX - prevMouse.x) * 0.008;
      sph.phi = Math.max(0.15, Math.min(1.5, sph.phi - (e.clientY - prevMouse.y) * 0.008));
      prevMouse = { x: e.clientX, y: e.clientY };
      updateCamera();
    };
    const onUp = () => { isDragging = false; };
    const onWheel = (e) => {
      sph.r = Math.max(4, Math.min(14, sph.r + e.deltaY * 0.012));
      updateCamera();
    };
    const onTouchStart = (e) => { if (e.touches.length === 1) { isDragging = true; prevMouse = { x: e.touches[0].clientX, y: e.touches[0].clientY }; } };
    const onTouchMove = (e) => {
      if (!isDragging || e.touches.length !== 1) return;
      sph.theta -= (e.touches[0].clientX - prevMouse.x) * 0.01;
      sph.phi = Math.max(0.15, Math.min(1.5, sph.phi - (e.touches[0].clientY - prevMouse.y) * 0.01));
      prevMouse = { x: e.touches[0].clientX, y: e.touches[0].clientY };
      updateCamera();
    };

    renderer.domElement.addEventListener("mousedown", onDown);
    window.addEventListener("mousemove", onMove);
    window.addEventListener("mouseup", onUp);
    renderer.domElement.addEventListener("wheel", onWheel);
    renderer.domElement.addEventListener("touchstart", onTouchStart);
    renderer.domElement.addEventListener("touchmove", onTouchMove);
    renderer.domElement.addEventListener("touchend", onUp);

    const handleResize = () => {
      const w = container.clientWidth, h = container.clientHeight;
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
      ceilingLight.intensity = 1.1 + Math.sin(t * 0.4) * 0.06;
      renderer.render(scene, camera);
    };
    animate();

    return () => {
      cancelAnimationFrame(animId);
      renderer.domElement.removeEventListener("mousedown", onDown);
      window.removeEventListener("mousemove", onMove);
      window.removeEventListener("mouseup", onUp);
      renderer.domElement.removeEventListener("wheel", onWheel);
      renderer.domElement.removeEventListener("touchstart", onTouchStart);
      renderer.domElement.removeEventListener("touchmove", onTouchMove);
      renderer.domElement.removeEventListener("touchend", onUp);
      window.removeEventListener("resize", handleResize);
      renderer.dispose();
      if (container.contains(renderer.domElement)) container.removeChild(renderer.domElement);
    };
  }, [activeTab]);

  const facilities = ["AC Split", "Kamar Mandi Dalam", "WiFi 50 Mbps", "Lemari Pakaian", "Meja Belajar", "Listrik Included"];

  return (
    <div style={{ background: "#0d1117", minHeight: "100vh", color: "white", fontFamily: "'Inter', system-ui, sans-serif", maxWidth: 480, margin: "0 auto" }}>

      {/* Header */}
      <div style={{ padding: "18px 20px 14px", background: "linear-gradient(180deg, #111827 0%, #0d1117 100%)" }}>
        <div style={{ display: "flex", justifyContent: "space-between", alignItems: "flex-start" }}>
          <div>
            <div style={{ fontSize: 11, color: "#6b7280", letterSpacing: "2px", textTransform: "uppercase", marginBottom: 4 }}>
              Kos Putra — Jepara
            </div>
            <div style={{ fontSize: 22, fontWeight: 700, lineHeight: 1.2 }}>Kamar A1</div>
            <div style={{ fontSize: 13, color: "#9ca3af", marginTop: 3 }}>Tipe Standar · Lantai 2</div>
          </div>
          <div style={{ textAlign: "right" }}>
            <div style={{ fontSize: 24, fontWeight: 800, color: "#f59e0b" }}>800K</div>
            <div style={{ fontSize: 11, color: "#6b7280" }}>/ bulan</div>
            <div style={{ marginTop: 6, background: "#16a34a22", border: "1px solid #16a34a55", color: "#4ade80", fontSize: 11, padding: "2px 8px", borderRadius: 20 }}>
              ● Tersedia
            </div>
          </div>
        </div>
      </div>

      {/* Tab switcher */}
      <div style={{ display: "flex", borderBottom: "1px solid #1f2937", padding: "0 20px" }}>
        {[["3d", "🧊 3D View"], ["info", "📋 Info"]].map(([key, label]) => (
          <button key={key} onClick={() => setActiveTab(key)}
            style={{ flex: 1, background: "none", border: "none", color: activeTab === key ? "#f59e0b" : "#6b7280", fontWeight: activeTab === key ? 700 : 400, fontSize: 13, padding: "12px 0", cursor: "pointer", borderBottom: activeTab === key ? "2px solid #f59e0b" : "2px solid transparent" }}>
            {label}
          </button>
        ))}
      </div>

      {/* 3D Viewer */}
      {activeTab === "3d" && (
        <>
          <div ref={mountRef} style={{ width: "100%", height: 400, cursor: "grab", position: "relative", background: "#1a1f2e" }}>
            <div style={{ position: "absolute", bottom: 12, left: "50%", transform: "translateX(-50%)", background: "rgba(0,0,0,0.65)", padding: "5px 14px", borderRadius: 20, fontSize: 11, color: "#94a3b8", zIndex: 10, pointerEvents: "none", whiteSpace: "nowrap" }}>
              🖱 Drag putar · Scroll zoom
            </div>
          </div>

          {/* Quick stats */}
          <div style={{ display: "grid", gridTemplateColumns: "repeat(4,1fr)", gap: 8, padding: "14px 20px 0" }}>
            {[["📐", "3×4 m²", "Luas"], ["🛏", "Single", "Kasur"], ["🪟", "Ada", "Jendela"], ["🚿", "Dalam", "Kamar Mandi"]].map(([icon, val, label]) => (
              <div key={label} style={{ background: "#161b27", border: "1px solid #1f2937", borderRadius: 10, padding: "10px 6px", textAlign: "center" }}>
                <div style={{ fontSize: 16 }}>{icon}</div>
                <div style={{ fontSize: 12, fontWeight: 700, marginTop: 3 }}>{val}</div>
                <div style={{ fontSize: 10, color: "#6b7280", marginTop: 2 }}>{label}</div>
              </div>
            ))}
          </div>
        </>
      )}

      {/* Info tab */}
      {activeTab === "info" && (
        <div style={{ padding: "16px 20px" }}>
          <div style={{ fontSize: 13, fontWeight: 600, color: "#9ca3af", marginBottom: 12, textTransform: "uppercase", letterSpacing: 1 }}>Fasilitas</div>
          <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: 8 }}>
            {facilities.map(f => (
              <div key={f} style={{ display: "flex", alignItems: "center", gap: 8, background: "#161b27", border: "1px solid #1f2937", borderRadius: 8, padding: "10px 12px" }}>
                <div style={{ width: 6, height: 6, borderRadius: "50%", background: "#f59e0b", flexShrink: 0 }} />
                <span style={{ fontSize: 12, color: "#d1d5db" }}>{f}</span>
              </div>
            ))}
          </div>

          <div style={{ fontSize: 13, fontWeight: 600, color: "#9ca3af", margin: "18px 0 10px", textTransform: "uppercase", letterSpacing: 1 }}>Peraturan Kos</div>
          {["Tidak merokok di dalam kamar", "Tamu dilarang menginap", "Jam malam pukul 22.00"].map(r => (
            <div key={r} style={{ fontSize: 12, color: "#9ca3af", padding: "6px 0", borderBottom: "1px solid #1f2937" }}>• {r}</div>
          ))}

          <div style={{ fontSize: 13, fontWeight: 600, color: "#9ca3af", margin: "18px 0 10px", textTransform: "uppercase", letterSpacing: 1 }}>Harga</div>
          {[["Bulanan", "Rp 800.000"], ["3 Bulan", "Rp 2.250.000"], ["Tahunan", "Rp 8.500.000"]].map(([label, price]) => (
            <div key={label} style={{ display: "flex", justifyContent: "space-between", fontSize: 13, padding: "8px 0", borderBottom: "1px solid #1f2937" }}>
              <span style={{ color: "#9ca3af" }}>{label}</span>
              <span style={{ fontWeight: 700, color: "#f59e0b" }}>{price}</span>
            </div>
          ))}
        </div>
      )}

      {/* CTA */}
      <div style={{ padding: "16px 20px 24px" }}>
        <button style={{ width: "100%", background: "linear-gradient(135deg, #f59e0b, #d97706)", color: "#0d1117", border: "none", borderRadius: 12, padding: "15px", fontWeight: 800, fontSize: 15, cursor: "pointer", letterSpacing: 0.5 }}>
          Sewa Kamar Ini
        </button>
        <button style={{ width: "100%", background: "transparent", color: "#f59e0b", border: "1px solid #f59e0b44", borderRadius: 12, padding: "12px", fontWeight: 600, fontSize: 13, cursor: "pointer", marginTop: 8 }}>
          Jadwalkan Survey
        </button>
      </div>
    </div>
  );
}
