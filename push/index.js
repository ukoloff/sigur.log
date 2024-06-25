const path = require('path')
const cp = require('child_process')

const cwd = path.join(__dirname, '../nc.ekb.ru/omz/service')

function rSync() {
  return new Promise(run)

  function run(resolve, reject) {
    cp.spawn('rsync', [
      '-e',
      'C:\\Users\\s.ukolov\\scoop\\apps\\cwrsync\\6.3.0\\bin\\ssh.exe -o HostKeyAlgorithms=+ssh-dss -o PubkeyAcceptedKeyTypes=+ssh-rsa',
      '-a',
      'sigur',
      'stas@10.33.10.104:dev/',
    ], {
      cwd: cwd,
      stdio: 'inherit'
    })
  }
}


rSync()
