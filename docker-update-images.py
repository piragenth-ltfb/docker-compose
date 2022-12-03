import os

docker_images = os.popen("docker images --format {{'.Repository'}}").read()
image = ''
list = []

def formating_pulling(docker_images, image, list, counts=0):
    print(os.system('docker images'))
#Formating docker_images list and renaming
    for superate in docker_images:
        if superate != '\n':
            image += superate
        else:
            list.append(image)
            image = ''
            counts+= 1
    print(f'Total {counts} Images')
    print(list)
    print(os.system('docker run --rm \
    -v /var/run/docker.sock:/var/run/docker.sock \
    containrrr/watchtower \
    --run-once'))
    for images in list:
        print(os.system(f'docker pull {images}'))
formating_pulling(docker_images,image,list)

print('Remove <none> tagged images')

print(os.system('docker rmi $(docker images --filter "dangling=true" -q --no-trunc)'))

