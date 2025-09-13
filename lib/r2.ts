import { S3Client } from '@aws-sdk/client-s3'
import { getSignedUrl } from '@aws-sdk/s3-request-presigner'
import { GetObjectCommand, PutObjectCommand } from '@aws-sdk/client-s3'

const r2Client = new S3Client({
  region: 'auto',
  endpoint: process.env.R2_ENDPOINT,
  credentials: {
    accessKeyId: process.env.R2_ACCESS_KEY_ID!,
    secretAccessKey: process.env.R2_SECRET_ACCESS_KEY!,
  },
})

export { r2Client }

export async function getPresignedUploadUrl(key: string, contentType: string) {
  const command = new PutObjectCommand({
    Bucket: process.env.R2_BUCKET,
    Key: key,
    ContentType: contentType,
  })

  return await getSignedUrl(r2Client, command, { expiresIn: 3600 })
}

export async function getPresignedDownloadUrl(key: string) {
  const command = new GetObjectCommand({
    Bucket: process.env.R2_BUCKET,
    Key: key,
  })

  return await getSignedUrl(r2Client, command, { expiresIn: 3600 })
}

export function getPublicUrl(key: string) {
  return `${process.env.R2_PUBLIC_URL}/${key}`
}
