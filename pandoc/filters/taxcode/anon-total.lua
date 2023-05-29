function Span(span)
if span.classes:includes('personaldata') and span.classes:includes('taxcode') then
  span.content = pandoc.Str('xxxxxxxxxxx')
  local value, position = span.classes:find('taxcode')
  span.classes:remove(position)
end
return span
end