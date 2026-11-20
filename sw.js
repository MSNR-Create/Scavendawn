const V='scavendawn-v19',FILES=["./", "index.html", "manifest.json", "icon-192.png", "icon-512.png", "fonts/dotgothic16-japanese-400-normal.woff2", "fonts/dotgothic16-latin-400-normal.woff2", "help/hud.jpg", "help/pc.jpg", "help/dialog.jpg", "help/night.jpg", "help/shelter.jpg", "help/bag.jpg", "help/en/hud.jpg", "help/en/pc.jpg", "help/en/dialog.jpg", "help/en/night.jpg", "help/en/shelter.jpg", "help/en/bag.jpg"];
self.addEventListener('install',e=>{e.waitUntil(caches.open(V).then(c=>c.addAll(FILES)));self.skipWaiting();});
self.addEventListener('activate',e=>{e.waitUntil(caches.keys().then(k=>Promise.all(k.filter(x=>x!==V).map(x=>caches.delete(x)))));self.clients.claim();});
self.addEventListener('fetch',e=>{const u=new URL(e.request.url);
  if(e.request.method!=='GET'||u.pathname.endsWith('rank.php'))return;
  e.respondWith(fetch(e.request).then(r=>{const c=r.clone();caches.open(V).then(x=>x.put(e.request,c));return r;}).catch(()=>caches.match(e.request)));});
