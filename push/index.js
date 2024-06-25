const os = require('node:os')
const path = require('node:path')
const cp = require('node:child_process')
const tm = require('node:timers/promises')
const c7r = require('chokidar')

const cwd = path.join(__dirname, '../nc.ekb.ru/omz/service')

c7r.watch(path.join(cwd, 'sigur'))
  .on('all', watcher)

function rSync() {
  return new Promise(run)

  function run(resolve, reject) {
    cp.spawn('rsync', [
      '-e',
      path.join(os.homedir(),
        'scoop/apps/cwrsync/current/bin/ssh.exe -o HostKeyAlgorithms=+ssh-dss -o PubkeyAcceptedKeyTypes=+ssh-rsa'),
      '-a',
      '--delete',
      'sigur',
      'stas@10.33.10.104:dev/',
    ], {
      cwd: cwd,
      stdio: 'inherit'
    })
      .on('error', reject)
      .on('close', resolve)
  }
}

var state = 0

function watcher(event, path) {
  switch (state) {
    case 0: //  Waiting for event(s)
      state = 1
      tm.setTimeout(100)
        .then(fire)
      break
    case 2: // Running rSync
      state = 3
  }
}

var startAt
function fire() {
  state = 2
  startAt = new Date()
  console.log(`<rsync at="${startAt.toLocaleString()}">`)
  rSync()
    .then(synced)
    .catch(console.log)
}

function synced() {
  console.log(`</rsync elapsed="${(new Date() - startAt) / 1000}">`)
  if (state == 3) {
    fire()
    return
  }
  state = 0
}
